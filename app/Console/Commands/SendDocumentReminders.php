<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Mail\DocumentReminderMail;
use App\Models\Document;
use App\Models\ReminderLog;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendDocumentReminders extends Command
{
    protected $signature = 'reminders:send';

    protected $description = 'Email company admins about documents expiring in 30, 15, or 7 days, or today';

    private int $sent = 0;

    private int $skipped = 0;

    private int $failed = 0;

    public function handle(): int
    {
        $this->sent = 0;
        $this->skipped = 0;
        $this->failed = 0;

        foreach ([30, 15, 7, 0] as $days) {
            Document::query()
                ->whereDate('expiry_date', now()->addDays($days)->toDateString())
                ->each(fn (Document $document) => $this->send($document, $days));
        }

        $this->info("Sent: {$this->sent}. Skipped: {$this->skipped}. Failed: {$this->failed}.");

        return self::SUCCESS;
    }

    private function send(Document $document, int $daysBefore): void
    {
        $expiresOn = $document->expiry_date->toDateString();

        $alreadySent = ReminderLog::query()
            ->where('document_id', $document->id)
            ->where('days_before', $daysBefore)
            ->whereDate('expires_on', $expiresOn)
            ->exists();

        if ($alreadySent) {
            $this->skipped++;

            return;
        }

        $admins = $this->deliverableAdmins($document->company_id);

        if ($admins->isEmpty()) {
            $this->skipped++;

            return;
        }

        $document->loadMissing(['category', 'contractor']);

        try {
            $message = new DocumentReminderMail($document, $daysBefore);

            if (config('mail.default') === 'log') {
                Mail::to($admins)->sendNow($message);
            } else {
                Mail::to($admins)->queue($message);
            }

            ReminderLog::query()->create([
                'document_id' => $document->id,
                'days_before' => $daysBefore,
                'expires_on' => $expiresOn,
                'sent_at' => now(),
            ]);

            $this->sent++;
        } catch (Throwable $exception) {
            $this->failed++;

            Log::error('Document reminder failed.', [
                'document_id' => $document->id,
                'recipients' => $admins->pluck('email')->all(),
                'exception' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @return Collection<int, User>
     */
    private function deliverableAdmins(int $companyId): Collection
    {
        return User::query()
            ->where('company_id', $companyId)
            ->where('role', UserRole::Admin)
            ->get()
            ->filter(fn (User $user): bool => filter_var($user->email, FILTER_VALIDATE_EMAIL) !== false)
            ->values();
    }
}

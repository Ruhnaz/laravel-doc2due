<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Mail\PlanExpiryMail;
use App\Models\Company;
use App\Models\Plan;
use App\Models\PlanReminderLog;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ExpirePlans extends Command
{
    protected $signature = 'plans:expire';

    protected $description = 'Email admins before a yearly plan ends, then move expired plans back to Starter';

    public function handle(): int
    {
        foreach ([30, 15, 7] as $days) {
            Company::query()
                ->with('plan')
                ->whereDate('plan_expires_at', now()->addDays($days)->toDateString())
                ->each(fn (Company $company) => $this->remind($company, $days));
        }

        $starterId = Plan::query()->where('slug', 'starter')->value('id');

        Company::query()
            ->whereNotNull('plan_expires_at')
            ->whereDate('plan_expires_at', '<', now()->toDateString())
            ->where('plan_id', '!=', $starterId)
            ->update([
                'plan_id' => $starterId,
                'plan_expires_at' => null,
            ]);

        return self::SUCCESS;
    }

    private function remind(Company $company, int $daysBefore): void
    {
        $expiresOn = $company->plan_expires_at->toDateString();

        $alreadySent = PlanReminderLog::query()
            ->where('company_id', $company->id)
            ->where('days_before', $daysBefore)
            ->whereDate('expires_on', $expiresOn)
            ->exists();

        if ($alreadySent) {
            return;
        }

        $admins = $this->deliverableAdmins($company->id);

        if ($admins->isEmpty()) {
            return;
        }

        try {
            Mail::to($admins)->queue(new PlanExpiryMail($company, $daysBefore));

            PlanReminderLog::query()->create([
                'company_id' => $company->id,
                'days_before' => $daysBefore,
                'expires_on' => $expiresOn,
                'sent_at' => now(),
            ]);
        } catch (Throwable $exception) {
            Log::error('Plan reminder failed.', [
                'company_id' => $company->id,
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

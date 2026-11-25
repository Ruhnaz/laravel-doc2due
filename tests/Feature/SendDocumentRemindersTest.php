<?php

namespace Tests\Feature;

use App\Mail\DocumentReminderMail;
use App\Models\Category;
use App\Models\Document;
use App\Models\ReminderLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendDocumentRemindersTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_emails_each_threshold_once_including_expiry_day(): void
    {
        Mail::fake();

        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create(['company_id' => $admin->company_id]);

        foreach ([30, 15, 7, 0, -1, 10] as $days) {
            Document::factory()->create([
                'company_id' => $admin->company_id,
                'category_id' => $category->id,
                'title' => 'Doc '.$days,
                'expiry_date' => now()->addDays($days)->toDateString(),
            ]);
        }

        $this->artisan('reminders:send')->assertSuccessful();
        $this->artisan('reminders:send')->assertSuccessful();

        Mail::assertQueued(DocumentReminderMail::class, 4);
        Mail::assertNotQueued(DocumentReminderMail::class, fn (DocumentReminderMail $mail) => $mail->document->title === 'Doc -1');
        Mail::assertNotQueued(DocumentReminderMail::class, fn (DocumentReminderMail $mail) => $mail->document->title === 'Doc 10');
        Mail::assertQueued(DocumentReminderMail::class, fn (DocumentReminderMail $mail) => $mail->document->title === 'Doc 0' && $mail->daysBefore === 0);
        $this->assertSame(4, ReminderLog::query()->count());
    }

    public function test_changed_expiry_date_sends_a_new_reminder(): void
    {
        Mail::fake();

        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create(['company_id' => $admin->company_id]);
        $document = Document::factory()->create([
            'company_id' => $admin->company_id,
            'category_id' => $category->id,
            'expiry_date' => now()->addDays(30)->toDateString(),
        ]);

        $this->artisan('reminders:send')->assertSuccessful();

        $document->update([
            'expiry_date' => now()->addDays(15)->toDateString(),
        ]);

        $this->artisan('reminders:send')->assertSuccessful();

        Mail::assertQueued(DocumentReminderMail::class, 2);
        $this->assertSame(2, ReminderLog::query()->where('document_id', $document->id)->count());
    }

    public function test_admin_with_an_invalid_email_is_skipped(): void
    {
        Mail::fake();

        $admin = User::factory()->admin()->create([
            'email' => 'not-an-email',
        ]);
        $category = Category::factory()->create(['company_id' => $admin->company_id]);

        Document::factory()->create([
            'company_id' => $admin->company_id,
            'category_id' => $category->id,
            'expiry_date' => now()->addDays(7)->toDateString(),
        ]);

        $this->artisan('reminders:send')->assertSuccessful();

        Mail::assertNothingQueued();
        $this->assertSame(0, ReminderLog::query()->count());
    }
}

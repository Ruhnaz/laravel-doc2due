<?php

namespace Tests\Feature;

use App\Mail\PlanExpiryMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ExpirePlansTest extends TestCase
{
    use RefreshDatabase;

    public function test_plan_reminder_is_sent_at_15_days_and_not_twice(): void
    {
        Mail::fake();

        $admin = User::factory()->admin()->create();
        $planId = DB::table('plans')->where('slug', 'basic')->value('id');

        $admin->company->update([
            'plan_id' => $planId,
            'plan_expires_at' => now()->addDays(15)->toDateString(),
        ]);

        $this->artisan('plans:expire')->assertSuccessful();
        $this->artisan('plans:expire')->assertSuccessful();

        Mail::assertQueued(PlanExpiryMail::class, 1);
        Mail::assertQueued(PlanExpiryMail::class, fn (PlanExpiryMail $mail) => $mail->daysBefore === 15);
    }
}

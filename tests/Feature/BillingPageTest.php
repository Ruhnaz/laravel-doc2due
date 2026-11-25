<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Document;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_sidebar_shows_the_company_plan_and_document_count(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create(['company_id' => $admin->company_id]);
        Document::factory()->create([
            'company_id' => $admin->company_id,
            'category_id' => $category->id,
        ]);

        $other = User::factory()->admin()->create();
        Document::factory()->create([
            'company_id' => $other->company_id,
            'category_id' => Category::factory()->create(['company_id' => $other->company_id])->id,
        ]);

        $this->actingAs($admin)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Starter plan')
            ->assertSee('1 / 2')
            ->assertSee('Upgrade')
            ->assertDontSee('Basic plan')
            ->assertDontSee('12 / 50');

        $employee = User::factory()->create(['company_id' => $admin->company_id]);

        $this->actingAs($employee)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Starter plan')
            ->assertSee('1 / 2')
            ->assertDontSee('Upgrade');
    }

    public function test_billing_page_shows_at_most_five_payments(): void
    {
        $admin = User::factory()->admin()->create();
        $planId = Plan::query()->where('slug', 'basic')->value('id');

        foreach (range(1, 6) as $number) {
            $payment = Payment::query()->create([
                'company_id' => $admin->company_id,
                'plan_id' => $planId,
                'razorpay_order_id' => 'order_history_'.$number,
                'razorpay_payment_id' => 'pay_history_'.$number,
                'amount' => 10000,
                'currency' => 'INR',
                'status' => 'paid',
                'paid_at' => now()->subDays(7 - $number),
            ]);
            $payment->forceFill([
                'created_at' => now()->subDays(7 - $number),
            ])->save();
        }

        $other = User::factory()->admin()->create();
        Payment::query()->create([
            'company_id' => $other->company_id,
            'plan_id' => $planId,
            'razorpay_order_id' => 'order_other_company',
            'razorpay_payment_id' => 'pay_other_company',
            'amount' => 10000,
            'currency' => 'INR',
            'status' => 'paid',
        ]);

        $this->actingAs($admin)->get(route('billing.index'))
            ->assertOk()
            ->assertSee('pay_history_6')
            ->assertSee('pay_history_2')
            ->assertSee('₹100.00')
            ->assertSee('View all payments')
            ->assertDontSee('pay_history_1')
            ->assertDontSee('pay_other_company');

        $this->actingAs($admin)->get(route('billing.payments'))
            ->assertOk()
            ->assertSee('pay_history_1')
            ->assertDontSee('pay_other_company');
    }

    public function test_employee_cannot_view_all_payments(): void
    {
        $admin = User::factory()->admin()->create();
        $employee = User::factory()->create(['company_id' => $admin->company_id]);

        $this->actingAs($employee)->get(route('billing.payments'))->assertForbidden();
    }

    public function test_billing_page_shows_an_empty_payment_state(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('billing.index'))
            ->assertOk()
            ->assertSee('No payments yet.');
    }
}

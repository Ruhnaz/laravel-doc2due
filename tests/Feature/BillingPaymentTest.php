<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use App\Services\RazorpayPayments;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class BillingPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_payment_applies_the_plan_immediately(): void
    {
        $this->freezeTime();

        $admin = User::factory()->admin()->create();
        $basic = Plan::query()->where('slug', 'basic')->firstOrFail();
        $payment = $this->pendingPayment($admin, $basic, 'order_verified');

        $this->partialMock(RazorpayPayments::class, function (MockInterface $mock): void {
            $mock->shouldReceive('signatureIsValid')->once()->andReturn(true);
        });

        $response = $this->actingAs($admin)->postJson(route('billing.verify'), [
            'razorpay_order_id' => 'order_verified',
            'razorpay_payment_id' => 'pay_verified',
            'razorpay_signature' => 'signature',
        ]);

        $response->assertOk()->assertExactJson([
            'status' => 'paid',
            'plan' => 'Basic',
        ]);

        $company = $admin->company->fresh();

        $this->assertSame($basic->id, $company->plan_id);
        $this->assertSame(now()->addYear()->toDateString(), $company->plan_expires_at->toDateString());
        $this->assertSame('paid', $payment->fresh()->status);
        $this->assertSame('pay_verified', $payment->fresh()->razorpay_payment_id);

        $this->actingAs($admin)->get(route('billing.index'))
            ->assertOk()
            ->assertSee('Payment received. Your plan is active for one year.');
    }

    public function test_verified_payment_without_json_redirects_to_billing(): void
    {
        $admin = User::factory()->admin()->create();
        $basic = Plan::query()->where('slug', 'basic')->firstOrFail();
        $this->pendingPayment($admin, $basic, 'order_redirect');

        $this->partialMock(RazorpayPayments::class, function (MockInterface $mock): void {
            $mock->shouldReceive('signatureIsValid')->once()->andReturn(true);
        });

        $this->actingAs($admin)->post(route('billing.verify'), [
            'razorpay_order_id' => 'order_redirect',
            'razorpay_payment_id' => 'pay_redirect',
            'razorpay_signature' => 'signature',
        ])->assertRedirect(route('billing.index'))
            ->assertSessionHas('success', 'Payment received. Your plan is active for one year.');

        $this->assertSame($basic->id, $admin->company->fresh()->plan_id);
    }

    public function test_invalid_signature_does_not_change_the_plan(): void
    {
        $admin = User::factory()->admin()->create();
        $starterId = $admin->company->plan_id;
        $basic = Plan::query()->where('slug', 'basic')->firstOrFail();
        $payment = $this->pendingPayment($admin, $basic, 'order_invalid');

        $this->partialMock(RazorpayPayments::class, function (MockInterface $mock): void {
            $mock->shouldReceive('signatureIsValid')->once()->andReturn(false);
        });

        $this->actingAs($admin)->postJson(route('billing.verify'), [
            'razorpay_order_id' => 'order_invalid',
            'razorpay_payment_id' => 'pay_invalid',
            'razorpay_signature' => 'invalid',
        ])->assertStatus(422);

        $this->assertSame($starterId, $admin->company->fresh()->plan_id);
        $this->assertNull($admin->company->fresh()->plan_expires_at);
        $this->assertSame('pending', $payment->fresh()->status);
    }

    public function test_verify_and_webhook_apply_the_plan_only_once(): void
    {
        $this->freezeTime();

        $admin = User::factory()->admin()->create();
        $basic = Plan::query()->where('slug', 'basic')->firstOrFail();
        $payment = $this->pendingPayment($admin, $basic, 'order_once', 250000);

        $this->partialMock(RazorpayPayments::class, function (MockInterface $mock): void {
            $mock->shouldReceive('signatureIsValid')->once()->andReturn(true);
            $mock->shouldReceive('webhookIsValid')->once()->andReturn(true);
        });

        $this->actingAs($admin)->postJson(route('billing.verify'), [
            'razorpay_order_id' => 'order_once',
            'razorpay_payment_id' => 'pay_from_checkout',
            'razorpay_signature' => 'signature',
        ])->assertOk();

        $expiresOn = now()->addYear()->toDateString();

        $this->travel(1)->day();

        $this->postJson(route('webhooks.razorpay'), [
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_from_webhook',
                        'order_id' => 'order_once',
                        'amount' => 250000,
                    ],
                ],
            ],
        ], ['X-Razorpay-Signature' => 'test'])->assertOk();

        $company = $admin->company->fresh();
        $payment->refresh();

        $this->assertSame($basic->id, $company->plan_id);
        $this->assertSame($expiresOn, $company->plan_expires_at->toDateString());
        $this->assertSame('paid', $payment->status);
        $this->assertSame('pay_from_checkout', $payment->razorpay_payment_id);
        $this->assertSame(1, Payment::query()->where('company_id', $admin->company_id)->count());
    }

    public function test_payment_status_is_limited_to_the_signed_in_company(): void
    {
        $admin = User::factory()->admin()->create();
        $other = User::factory()->admin()->create();
        $basic = Plan::query()->where('slug', 'basic')->firstOrFail();
        $this->pendingPayment($admin, $basic, 'order_mine');
        $this->pendingPayment($other, $basic, 'order_theirs');

        $this->actingAs($admin)->getJson(route('billing.status', [
            'razorpay_order_id' => 'order_mine',
        ]))->assertOk()->assertExactJson([
            'status' => 'pending',
            'plan_name' => 'Starter',
            'plan_expires_at' => null,
        ]);

        $this->actingAs($admin)->getJson(route('billing.status', [
            'razorpay_order_id' => 'order_theirs',
        ]))->assertNotFound();

        $employee = User::factory()->create(['company_id' => $admin->company_id]);

        $this->actingAs($employee)->getJson(route('billing.status', [
            'razorpay_order_id' => 'order_mine',
        ]))->assertForbidden();
    }

    private function pendingPayment(User $admin, Plan $plan, string $orderId, int $amount = 100): Payment
    {
        return Payment::query()->create([
            'company_id' => $admin->company_id,
            'plan_id' => $plan->id,
            'razorpay_order_id' => $orderId,
            'amount' => $amount,
            'currency' => 'INR',
            'status' => 'pending',
        ]);
    }
}

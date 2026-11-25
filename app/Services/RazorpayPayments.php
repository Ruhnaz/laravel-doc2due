<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Payment;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayPayments
{
    public function createOrder(Company $company, Plan $plan): Payment
    {
        $amount = $plan->inr() * 100;

        $order = $this->client()->order->create([
            'amount' => $amount,
            'currency' => 'INR',
            'receipt' => 'c'.$company->id.'-'.now()->timestamp,
            'notes' => [
                'company_id' => (string) $company->id,
                'plan_id' => (string) $plan->id,
            ],
        ]);

        return Payment::query()->create([
            'company_id' => $company->id,
            'plan_id' => $plan->id,
            'razorpay_order_id' => $order['id'],
            'amount' => $amount,
            'currency' => 'INR',
            'status' => 'pending',
        ]);
    }

    public function signatureIsValid(string $orderId, string $paymentId, string $signature): bool
    {
        try {
            $this->client()->utility->verifyPaymentSignature([
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature,
            ]);

            return true;
        } catch (SignatureVerificationError) {
            return false;
        }
    }

    public function webhookIsValid(string $body, ?string $signature): bool
    {
        if ($signature === null || $signature === '') {
            return false;
        }

        try {
            $this->client()->utility->verifyWebhookSignature(
                $body,
                $signature,
                (string) config('services.razorpay.webhook_secret'),
            );

            return true;
        } catch (SignatureVerificationError) {
            return false;
        }
    }

    public function markPaid(Payment $payment, string $paymentId): void
    {
        DB::transaction(function () use ($payment, $paymentId): void {
            $locked = Payment::query()
                ->withoutGlobalScopes()
                ->whereKey($payment->getKey())
                ->lockForUpdate()
                ->first();

            if ($locked === null || $locked->status === 'paid') {
                return;
            }

            $locked->update([
                'razorpay_payment_id' => $paymentId,
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            $locked->company()->update([
                'plan_id' => $locked->plan_id,
                'plan_expires_at' => now()->addYear()->toDateString(),
            ]);
        });
    }

    private function client(): Api
    {
        return new Api(
            (string) config('services.razorpay.key'),
            (string) config('services.razorpay.secret'),
        );
    }
}

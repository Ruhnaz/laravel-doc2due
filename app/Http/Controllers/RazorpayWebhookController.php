<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\RazorpayPayments;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RazorpayWebhookController extends Controller
{
    public function __invoke(Request $request, RazorpayPayments $razorpay): Response
    {
        $body = $request->getContent();

        abort_unless($razorpay->webhookIsValid($body, $request->header('X-Razorpay-Signature')), 400);

        $payload = json_decode($body, true);
        $entity = $payload['payload']['payment']['entity'] ?? null;

        if (($payload['event'] ?? '') !== 'payment.captured' || ! is_array($entity)) {
            return response('ok');
        }

        $payment = Payment::query()->where('razorpay_order_id', $entity['order_id'] ?? '')->first();

        if ($payment && (int) ($entity['amount'] ?? 0) === (int) $payment->amount && ! empty($entity['id'])) {
            $razorpay->markPaid($payment, $entity['id']);
        }

        return response('ok');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePlanRequest;
use App\Models\Payment;
use App\Models\Plan;
use App\Services\RazorpayPayments;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BillingController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $company = auth()->user()->company->load('plan');

        $this->authorize('viewBilling', $company);

        return view('billing.index', [
            'company' => $company,
            'plans' => Plan::query()->orderBy('price')->get(),
            'payments' => $company->payments()->with('plan')->latest()->limit(5)->get(),
            'usage' => $company->documents()->count(),
        ]);
    }

    public function payments(): View
    {
        $company = auth()->user()->company;

        $this->authorize('viewBilling', $company);

        return view('billing.payments', [
            'payments' => $company->payments()->with('plan')->latest()->paginate(10),
        ]);
    }

    public function status(Request $request): JsonResponse
    {
        $company = $request->user()->company()->with('plan')->first();

        $this->authorize('viewBilling', $company);

        $data = $request->validate([
            'razorpay_order_id' => ['required', 'string'],
        ]);

        $payment = Payment::query()
            ->where('company_id', $company->id)
            ->where('razorpay_order_id', $data['razorpay_order_id'])
            ->first();

        abort_if($payment === null, 404);

        return response()->json([
            'status' => $payment->status,
            'plan_name' => $company->plan?->name,
            'plan_expires_at' => $company->plan_expires_at?->toDateString(),
        ]);
    }

    public function update(UpdatePlanRequest $request): RedirectResponse
    {
        abort_unless(app()->environment('local'), 404);

        $request->user()->company->update([
            'plan_id' => $request->integer('plan_id'),
        ]);

        return redirect()->route('billing.index')->with('success', 'Plan updated. Payment collection will be added later.');
    }

    public function order(Request $request, RazorpayPayments $razorpay): JsonResponse
    {
        $company = $request->user()->company;

        $this->authorize('updatePlan', $company);

        $plan = Plan::query()->findOrFail($request->integer('plan_id'));
        abort_if($plan->inr() < 1, 422);

        $payment = $razorpay->createOrder($company, $plan);

        return response()->json([
            'key' => config('services.razorpay.key'),
            'order_id' => $payment->razorpay_order_id,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'name' => 'Doc2Due',
            'description' => $plan->name.' yearly',
        ]);
    }

    public function verify(Request $request, RazorpayPayments $razorpay): JsonResponse|RedirectResponse
    {
        $company = $request->user()->company;

        $this->authorize('updatePlan', $company);

        $data = $request->validate([
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $payment = Payment::query()
            ->where('company_id', $company->id)
            ->where('razorpay_order_id', $data['razorpay_order_id'])
            ->firstOrFail();

        abort_unless($razorpay->signatureIsValid(
            $data['razorpay_order_id'],
            $data['razorpay_payment_id'],
            $data['razorpay_signature'],
        ), 422);

        $razorpay->markPaid($payment, $data['razorpay_payment_id']);

        $company->refresh()->load('plan');

        if ($request->expectsJson()) {
            $request->session()->flash('success', 'Payment received. Your plan is active for one year.');

            return response()->json([
                'status' => 'paid',
                'plan' => $company->plan->name,
            ]);
        }

        return redirect()->route('billing.index')->with('success', 'Payment received. Your plan is active for one year.');
    }
}

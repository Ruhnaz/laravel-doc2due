<x-app-layout>
    <x-slot name="header">
        <div class="text-left">
            <h1 class="text-3xl font-bold text-ink">Payments</h1>
            <p class="mt-1 text-sm text-muted">Every payment for your company, newest first.</p>
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            <a href="{{ route('billing.index') }}" class="inline-flex text-sm font-semibold text-brand">Back to billing</a>

            <x-card :padding="false">
                <div class="border-b border-line px-4 py-4 sm:px-6 sm:py-5">
                    <h2 class="text-lg font-semibold text-ink">All payments</h2>
                </div>
                @if ($payments->isEmpty())
                    <p class="px-6 py-10 text-sm text-muted">No payments yet.</p>
                @else
                    <div class="divide-y divide-line md:hidden">
                        @foreach ($payments as $payment)
                            <div class="space-y-2 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="text-base font-semibold text-ink">{{ $payment->plan->name }}</p>
                                    <span class="inline-flex items-center rounded-full bg-status-valid-soft px-2.5 py-1 text-xs font-medium text-status-valid-text">{{ ucfirst($payment->status) }}</span>
                                </div>
                                <div class="grid grid-cols-1 gap-1 pt-1 text-sm text-body">
                                    <div class="flex items-center justify-between py-0.5">
                                        <span class="text-muted">Date:</span>
                                        <span class="font-medium tabular-nums text-ink">{{ ($payment->paid_at ?? $payment->created_at)->format('M j, Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between py-0.5">
                                        <span class="text-muted">Amount:</span>
                                        <span class="font-semibold tabular-nums text-ink">₹{{ number_format($payment->amount / 100, 2) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 py-0.5">
                                        <span class="text-muted">Payment ID:</span>
                                        <span class="truncate font-medium text-ink">{{ $payment->razorpay_payment_id ?: '—' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="hidden md:block">
                        <table class="w-full text-left text-sm">
                            <thead class="text-muted">
                                <tr class="border-b border-line">
                                    <th class="px-6 py-3 font-medium">Date</th>
                                    <th class="px-6 py-3 font-medium">Plan</th>
                                    <th class="px-6 py-3 font-medium">Amount</th>
                                    <th class="px-6 py-3 font-medium">Status</th>
                                    <th class="px-6 py-3 font-medium">Payment ID</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line">
                                @foreach ($payments as $payment)
                                    <tr class="hover:bg-brand-tint">
                                        <td class="px-6 py-4 tabular-nums text-body">{{ ($payment->paid_at ?? $payment->created_at)->format('M j, Y') }}</td>
                                        <td class="px-6 py-4 font-medium text-ink">{{ $payment->plan->name }}</td>
                                        <td class="px-6 py-4 tabular-nums text-body">₹{{ number_format($payment->amount / 100, 2) }}</td>
                                        <td class="px-6 py-4"><span class="inline-flex items-center rounded-full bg-status-valid-soft px-2.5 py-1 text-xs font-medium text-status-valid-text">{{ ucfirst($payment->status) }}</span></td>
                                        <td class="px-6 py-4 text-body">{{ $payment->razorpay_payment_id ?: '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="border-t border-line px-6 py-4">
                        {{ $payments->links('pagination.brand') }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>

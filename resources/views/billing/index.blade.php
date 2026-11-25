<x-app-layout>
    <x-slot name="header">
        <div class="text-left">
            <h1 class="text-3xl font-bold text-ink">Billing</h1>
            <p class="mt-1 text-sm text-muted">Manage your plan and document limit.</p>
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="mx-auto max-w-6xl space-y-8 px-4 sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-700 ring-1 ring-red-100">{{ session('error') }}</div>
            @endif

            <div id="payment-pending" class="hidden rounded-2xl bg-amber-50 px-4 py-3 text-sm text-amber-950 ring-1 ring-amber-100" role="status"></div>

            <div class="rounded-card border border-line bg-surface p-6">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-muted">Current plan</p>
                        <p class="mt-1 flex items-center gap-2 text-2xl font-bold text-ink">{{ $company->plan->name }} <x-badge variant="safe">Active</x-badge></p>
                        <p class="mt-1 text-sm text-muted">
                            {{ $company->plan->priceLabel() }}
                            · Expires {{ $company->plan_expires_at?->format('M j, Y') ?? '—' }}
                        </p>
                    </div>
                    <div class="w-full max-w-md">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-muted">Documents used</span>
                            <span class="font-semibold text-brand">{{ $usage }} / {{ $company->plan->document_limit }} documents</span>
                        </div>
                        <div class="mt-2 h-2 overflow-hidden rounded-full bg-brand-soft">
                            <div class="h-full rounded-full bg-brand" style="width: {{ min(100, $company->plan->document_limit ? round($usage / $company->plan->document_limit * 100) : 0) }}%"></div>
                        </div>
                        <p class="mt-2 text-sm text-muted">{{ max($company->plan->document_limit - $usage, 0) }} document slots left. Upgrade any time you need more.</p>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-bold text-ink">Choose your plan</h2>
                @php
                    $blurbs = [
                        'starter' => 'Try Doc2Due with your most important documents',
                        'basic' => 'Small teams keeping day-to-day documents current',
                        'business' => 'Growing companies with many contractors and records',
                        'professional' => 'Large document libraries across the business',
                    ];
                @endphp
                <div class="mt-8 grid items-start gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($plans as $plan)
                        <article @class(['relative flex flex-col bg-surface p-5 shadow-card', 'z-10 rounded-panel border-2 border-brand pt-8' => $plan->id === $company->plan_id, 'rounded-card border border-line' => $plan->id !== $company->plan_id])>
                            @if ($plan->id === $company->plan_id)
                                <p class="absolute left-1/2 top-0 -translate-x-1/2 -translate-y-1/2 rounded-full bg-brand px-3 py-1 text-xs font-semibold text-white">Your current plan</p>
                            @endif
                            <p class="text-xs font-semibold uppercase tracking-wide text-muted">{{ $plan->document_limit }} documents</p>
                            <h3 class="mt-1 text-xl font-bold text-ink">{{ $plan->name }}</h3>
                            <p class="mt-3 min-h-[3rem] text-sm text-muted">{{ $blurbs[$plan->slug] ?? $plan->name }}</p>
                            <p class="mt-4 text-3xl font-bold tabular-nums text-ink">{{ $plan->inr() > 0 ? '₹'.number_format($plan->inr()) : 'Free' }}</p>
                            <p class="text-sm text-muted">{{ $plan->inr() > 0 ? 'Per year' : 'Free to start' }}</p>
                            <div class="mt-4">
                                @if ($plan->id === $company->plan_id)
                                    <span class="inline-flex min-h-[44px] w-full items-center justify-center rounded-xl bg-brand-soft px-4 py-2.5 text-sm font-semibold text-brand">Current plan</span>
                                    @if ($plan->inr() > 0)
                                        <x-button type="button" class="mt-2 w-full min-h-[44px]" onclick="payNow({{ $plan->id }})">Pay now</x-button>
                                    @endif
                                @elseif ($plan->inr() > 0)
                                    <button type="button" class="w-full min-h-[44px] rounded-full border border-line px-4 py-2.5 text-sm font-semibold text-ink hover:bg-page" onclick="payNow({{ $plan->id }})">Upgrade to {{ $plan->name }}</button>
                                @else
                                    <span class="inline-flex min-h-[44px] w-full items-center justify-center rounded-full border border-line px-4 py-2.5 text-sm font-semibold text-ink">Downgrade to {{ $plan->name }}</span>
                                @endif
                            </div>
                            <p class="mt-4 flex items-start gap-2 border-t border-line pt-4 text-sm text-body"><span class="text-brand">✓</span> Up to {{ $plan->document_limit }} documents</p>
                        </article>
                    @endforeach
                </div>
                <p class="mt-6 text-sm text-muted">All prices are per year. Document limit is the total number of documents you can track at one time. Expires {{ $company->plan_expires_at?->format('M j, Y') ?? '—' }}.</p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-ink">Compare plans</h2>
                <div class="mt-4 overflow-hidden rounded-card border border-line bg-surface">
                    <!-- Mobile stacked cards below md -->
                    <div class="divide-y divide-line md:hidden">
                        @foreach ($plans as $plan)
                            <div @class(['p-4 space-y-1', 'bg-brand-tint' => $plan->id === $company->plan_id])>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="font-semibold text-ink">{{ $plan->name }}@if ($plan->id === $company->plan_id) <span class="text-brand font-normal">(current)</span>@endif</span>
                                    <span class="font-bold tabular-nums text-ink">{{ $plan->priceLabel() }}</span>
                                </div>
                                <p class="text-sm text-muted">Up to {{ $plan->document_limit }} documents</p>
                            </div>
                        @endforeach
                    </div>
                    <!-- Desktop table md and up -->
                    <table class="hidden md:table w-full text-left text-sm">
                        <thead class="text-muted">
                            <tr class="border-b border-line">
                                <th class="px-6 py-3 font-medium">Plan</th>
                                <th class="px-6 py-3 font-medium">Document limit</th>
                                <th class="px-6 py-3 font-medium">Price</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach ($plans as $plan)
                                <tr @class(['bg-brand-tint' => $plan->id === $company->plan_id])>
                                    <td class="px-6 py-4 font-medium text-ink">{{ $plan->name }}@if ($plan->id === $company->plan_id) (current)@endif</td>
                                    <td class="px-6 py-4">{{ $plan->document_limit }}</td>
                                    <td class="px-6 py-4 tabular-nums">{{ $plan->priceLabel() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <x-card :padding="false">
                <div class="flex items-center justify-between gap-3 border-b border-line px-4 py-4 sm:px-6 sm:py-5">
                    <h2 class="text-lg font-semibold text-ink">Payment history</h2>
                    <a href="{{ route('billing.payments') }}" class="text-sm font-semibold text-brand">View all payments</a>
                </div>
                @if ($payments->isEmpty())
                    <p class="px-6 py-10 text-sm text-muted">No payments yet.</p>
                @else
                    <!-- Mobile stacked cards below md -->
                    <div class="divide-y divide-line md:hidden">
                        @foreach ($payments as $payment)
                            <div class="p-4 space-y-2 hover:bg-brand-tint">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-ink text-base">{{ $payment->plan->name }}</p>
                                    </div>
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium bg-status-valid-soft text-status-valid-text">{{ ucfirst($payment->status) }}</span>
                                </div>
                                <div class="grid grid-cols-1 gap-1 text-sm text-body pt-1">
                                    <div class="flex items-center justify-between text-sm py-0.5">
                                        <span class="text-muted">Date:</span>
                                        <span class="font-medium tabular-nums text-ink">{{ ($payment->paid_at ?? $payment->created_at)->format('M j, Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm py-0.5">
                                        <span class="text-muted">Amount:</span>
                                        <span class="font-semibold tabular-nums text-ink">₹{{ number_format($payment->amount / 100, 2) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 text-sm py-0.5">
                                        <span class="text-muted">Payment ID:</span>
                                        <span class="truncate font-medium text-ink">{{ $payment->razorpay_payment_id ?: '—' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop table md and up -->
                    <div class="hidden md:block overflow-visible">
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
                                        <td class="px-6 py-4"><span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium bg-status-valid-soft text-status-valid-text">{{ ucfirst($payment->status) }}</span></td>
                                        <td class="px-6 py-4 text-body">{{ $payment->razorpay_payment_id ?: '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-card>
        </div>
    </div>

    <form id="razorpay-verify" method="POST" action="{{ route('billing.verify') }}" class="hidden">
        @csrf
        <input type="hidden" name="razorpay_order_id">
        <input type="hidden" name="razorpay_payment_id">
        <input type="hidden" name="razorpay_signature">
    </form>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        async function payNow(planId) {
            const response = await fetch(@json(route('billing.order')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ plan_id: planId }),
            });

            if (!response.ok) {
                return;
            }

            const data = await response.json();
            const checkout = new Razorpay({
                key: data.key,
                amount: data.amount,
                currency: data.currency,
                name: data.name,
                description: data.description,
                order_id: data.order_id,
                handler(payment) {
                    confirmPayment(payment);
                },
            });
            checkout.open();
        }

        const billingUrl = @json(route('billing.index'));
        const verifyUrl = @json(route('billing.verify'));
        const statusUrl = @json(route('billing.status'));
        let paymentFinished = false;

        function showPaymentPending(message) {
            const pending = document.getElementById('payment-pending');
            pending.textContent = message;
            pending.classList.remove('hidden');
        }

        function finishPayment() {
            if (paymentFinished) {
                return;
            }

            paymentFinished = true;
            window.location.assign(billingUrl);
        }

        function pollPlan(orderId) {
            const started = Date.now();

            async function check() {
                if (paymentFinished) {
                    return;
                }

                if (Date.now() - started >= 30000) {
                    showPaymentPending('Payment received. We are confirming it, please do not pay again');

                    return;
                }

                try {
                    const response = await fetch(statusUrl + '?razorpay_order_id=' + encodeURIComponent(orderId), {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (! response.ok) {
                        return;
                    }

                    const data = await response.json();

                    if (data.status === 'paid') {
                        finishPayment();
                    }
                } catch (error) {
                }
            }

            check();

            const timer = setInterval(() => {
                if (paymentFinished || Date.now() - started >= 30000) {
                    clearInterval(timer);

                    if (! paymentFinished) {
                        showPaymentPending('Payment received. We are confirming it, please do not pay again');
                    }

                    return;
                }

                check();
            }, 2000);
        }

        async function confirmPayment(payment) {
            const payload = {
                razorpay_order_id: payment.razorpay_order_id,
                razorpay_payment_id: payment.razorpay_payment_id,
                razorpay_signature: payment.razorpay_signature,
            };

            showPaymentPending('Payment received, updating your plan...');

            if (typeof fetch !== 'function') {
                const form = document.getElementById('razorpay-verify');
                form.razorpay_order_id.value = payload.razorpay_order_id;
                form.razorpay_payment_id.value = payload.razorpay_payment_id;
                form.razorpay_signature.value = payload.razorpay_signature;
                form.submit();

                return;
            }

            pollPlan(payload.razorpay_order_id);

            try {
                const response = await fetch(verifyUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify(payload),
                });

                if (response.ok) {
                    finishPayment();

                    return;
                }

                showPaymentPending('Payment received. We are confirming it, please do not pay again');
            } catch (error) {
                showPaymentPending('Payment received. We are confirming it, please do not pay again');
            }
        }
    </script>
</x-app-layout>

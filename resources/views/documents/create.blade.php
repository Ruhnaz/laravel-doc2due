<x-app-layout>
    <x-slot name="header">
        <div class="text-left">
            <p class="text-sm text-muted"><a href="{{ route('documents.index') }}" class="font-medium text-brand">Documents</a> <span class="text-muted">› Add document</span></p>
            <h1 class="mt-2 text-3xl font-bold text-ink">Add document</h1>
            <p class="mt-1 text-sm text-muted">Upload a certificate, license, insurance policy or contract and set when it expires.</p>
        </div>
    </x-slot>
    <div class="pb-10">
        <div class="mx-auto grid max-w-6xl gap-6 px-4 sm:px-6 lg:grid-cols-[minmax(0,1fr)_280px] lg:px-8">
            <div class="space-y-4">
                @if (session('error'))
                    <div class="rounded-2xl bg-danger-soft px-4 py-3 text-sm text-danger">
                        {{ session('error') }}
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('billing.index') }}" class="mt-2 block font-semibold">Upgrade your plan</a>
                        @endif
                    </div>
                @endif
                <x-card :padding="false">@include('documents._form')</x-card>
            </div>
            @php
                $account = auth()->user()->company->load('plan');
                $used = $account->documents()->count();
                $slots = max($account->plan->document_limit - $used, 0);
            @endphp
            <div class="space-y-4">
                <aside class="rounded-2xl border border-line bg-white p-6 shadow-card">
                    <h2 class="font-semibold text-ink">Your plan</h2>
                    <div class="mt-3 flex items-center justify-between text-sm">
                        <span>{{ $account->plan->name }} plan</span>
                        <span class="font-semibold text-brand">{{ $used }} / {{ $account->plan->document_limit }} documents</span>
                    </div>
                    <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-brand-soft">
                        <div class="h-full rounded-full bg-brand" style="width: {{ $account->plan->document_limit ? min(100, round($used / $account->plan->document_limit * 100)) : 0 }}%"></div>
                    </div>
                    <p class="mt-2 text-sm text-muted">{{ $slots }} document slots left.@if (auth()->user()->isAdmin()) <a href="{{ route('billing.index') }}" class="inline-flex min-h-[44px] items-center font-semibold text-brand">Upgrade plan</a>@endif</p>
                </aside>
                <aside class="rounded-2xl border border-line bg-white p-6 shadow-card">
                    <h2 class="font-semibold text-ink">Reminders for this document</h2>
                    <ul class="mt-4 space-y-2 text-sm text-body">
                        <li>Email 30 days before expiry</li>
                        <li>Email 15 days before expiry</li>
                        <li>Email 7 days before expiry</li>
                        <li>One alert once it has expired</li>
                    </ul>
                    <p class="mt-4 text-sm text-muted">Reminders go to your company admin.</p>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>

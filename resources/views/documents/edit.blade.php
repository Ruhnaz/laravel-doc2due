<x-app-layout>
    <x-slot name="header">
        <div class="text-left">
            <p class="text-sm text-muted"><a href="{{ route('documents.index') }}" class="font-medium text-brand">Documents</a> <span>› Edit document</span></p>
            <h1 class="mt-2 text-3xl font-bold text-ink">Edit document</h1>
            <p class="mt-1 text-sm text-muted">Update the dates, file, and notes for this document.</p>
        </div>
    </x-slot>
    <div class="pb-10">
        <div class="mx-auto grid max-w-6xl gap-6 px-4 sm:px-6 lg:grid-cols-[minmax(0,1fr)_280px] lg:px-8">
            <x-card :padding="false">@include('documents._form')</x-card>
            @php
                $account = auth()->user()->company->load('plan');
                $used = $account->documents()->count();
            @endphp
            <aside class="h-fit rounded-2xl border border-line bg-white p-6 shadow-card">
                <h2 class="font-semibold text-ink">Your plan</h2>
                <div class="mt-3 flex items-center justify-between text-sm">
                    <span>{{ $account->plan->name }} plan</span>
                    <span class="font-semibold text-brand">{{ $used }} / {{ $account->plan->document_limit }} documents</span>
                </div>
                <p class="mt-3 text-sm text-muted">Expires {{ $account->plan_expires_at?->format('M j, Y') ?? '—' }}</p>
            </aside>
        </div>
    </div>
</x-app-layout>

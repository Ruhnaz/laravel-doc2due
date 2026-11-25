<x-app-layout>
    <x-slot name="header">
        <div class="text-left">
            <p class="text-sm text-muted"><a href="{{ route('contractors.index') }}" class="font-medium text-brand">Contractors</a> <span>› Add contractor</span></p>
            <h1 class="mt-2 text-3xl font-bold text-ink">Add contractor</h1>
            <p class="mt-1 text-sm text-muted">Add a contractor or vendor so you can attach their documents.</p>
        </div>
    </x-slot>
    <div class="pb-10">
        <div class="mx-auto grid max-w-6xl gap-6 px-4 sm:px-6 lg:grid-cols-[minmax(0,1fr)_280px] lg:px-8">
            <x-card :padding="false">@include('contractors._form')</x-card>
            <div class="space-y-4">
                <aside class="rounded-2xl border border-line bg-white p-6 shadow-card">
                    <h2 class="font-semibold text-ink">Contractors do not need an account</h2>
                    <p class="mt-2 text-sm text-muted">A contractor is a record you attach documents to. They never log in. Reminders about their documents go to your company admin.</p>
                </aside>
                <aside class="rounded-2xl border border-line bg-white p-6 shadow-card">
                    <h2 class="font-semibold text-ink">What happens next</h2>
                    <p class="mt-2 text-sm text-muted">After saving, upload their insurance certificates, licenses or contracts. Each document gets its own expiry date and reminders.</p>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>

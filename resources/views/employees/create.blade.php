<x-app-layout>
    <x-slot name="header">
        <div class="text-left">
            <p class="text-sm text-muted"><a href="{{ route('employees.index') }}" class="font-medium text-brand">Employees</a> <span>› Add employee</span></p>
            <h1 class="mt-2 text-3xl font-bold text-ink">Add employee</h1>
            <p class="mt-1 text-sm text-muted">Give a teammate access to your company account.</p>
        </div>
    </x-slot>
    <div class="pb-10">
        <div class="mx-auto grid max-w-6xl gap-6 px-4 sm:px-6 lg:grid-cols-[minmax(0,1fr)_280px] lg:px-8">
            <x-card :padding="false">@include('employees._form')</x-card>
            <div class="space-y-4">
                <aside class="rounded-2xl border border-line bg-white p-6 shadow-card">
                    <h2 class="font-semibold text-ink">Admin</h2>
                    <p class="mt-2 text-sm text-muted">Manages employees, billing and company settings, as well as documents and contractors.</p>
                </aside>
                <aside class="rounded-2xl border border-line bg-white p-6 shadow-card">
                    <h2 class="font-semibold text-ink">Employee</h2>
                    <p class="mt-2 text-sm text-muted">Works with documents and contractors. Cannot manage employees or billing.</p>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>

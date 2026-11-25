<x-app-layout>
    <x-slot name="header">
        <div class="text-left">
            <p class="text-sm text-muted"><a href="{{ route('employees.index') }}" class="font-medium text-brand">Employees</a> <span>› Edit employee</span></p>
            <h1 class="mt-2 text-3xl font-bold text-ink">Edit employee</h1>
            <p class="mt-1 text-sm text-muted">Update this person's access to the company.</p>
        </div>
    </x-slot>
    <div class="pb-10">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <x-card :padding="false">@include('employees._form')</x-card>
        </div>
    </div>
</x-app-layout>

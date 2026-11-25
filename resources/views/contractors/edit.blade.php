<x-app-layout>
    <x-slot name="header">
        <div class="text-left">
            <p class="text-sm text-muted"><a href="{{ route('contractors.index') }}" class="font-medium text-brand">Contractors</a> <span>› Edit contractor</span></p>
            <h1 class="mt-2 text-3xl font-bold text-ink">Edit contractor</h1>
            <p class="mt-1 text-sm text-muted">Update the contact details for this contractor.</p>
        </div>
    </x-slot>
    <div class="pb-10">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <x-card :padding="false">@include('contractors._form')</x-card>
        </div>
    </div>
</x-app-layout>

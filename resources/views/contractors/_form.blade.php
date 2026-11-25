@php($editing = isset($contractor))
<div @if($editing) x-data="{ deleting: false }" @endif>
    <form method="POST" action="{{ $editing ? route('contractors.update', $contractor) : route('contractors.store') }}">
        @csrf
        @if ($editing) @method('PUT') @endif
        <div class="space-y-5 p-4 sm:p-6 md:p-8">
            <x-input name="name" label="Contractor or company name" :value="$editing ? $contractor->name : null" required autofocus />
            <div class="grid gap-5 md:grid-cols-2">
                <x-input name="email" label="Contact email (optional)" type="email" :value="$editing ? $contractor->email : null" />
                <x-input name="phone" label="Phone (optional)" :value="$editing ? $contractor->phone : null" />
            </div>
            <div>
                <label for="notes" class="block text-sm font-medium text-ink">Notes (optional)</label>
                <textarea id="notes" name="notes" rows="4" placeholder="Services provided, contact person, contract reference" class="mt-1.5 block w-full rounded-xl border-line text-sm text-ink shadow-sm focus:border-brand focus:ring-brand/20">{{ old('notes', $editing ? $contractor->notes : null) }}</textarea>
                @error('notes')<p class="mt-1.5 text-sm text-danger">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="flex flex-col gap-3 border-t border-line px-4 py-4 sm:px-6 md:flex-row md:items-center md:justify-between md:px-8">
            @if ($editing)
                <button type="button" form="delete-contractor-form" @click="deleting = true" class="inline-flex min-h-[44px] w-full items-center justify-center rounded-xl border border-danger-line px-4 py-2.5 text-sm font-semibold text-danger hover:bg-danger-soft md:w-auto">Delete contractor</button>
            @else
                <span></span>
            @endif
            <div class="flex flex-col gap-3 w-full md:w-auto md:flex-row md:items-center md:justify-end">
                <x-button :href="route('contractors.index')" variant="secondary" class="w-full md:w-auto">Cancel</x-button>
                <x-button class="w-full md:w-auto">{{ $editing ? 'Save changes' : 'Save contractor' }}</x-button>
            </div>
        </div>
    </form>
    @if ($editing)
        <x-delete-modal
            show="deleting"
            form-id="delete-contractor-form"
            :action="route('contractors.destroy', $contractor)"
            title="Delete this contractor?"
            :description="$contractor->name . ' will be removed. Documents linked to them stay in your company.'"
            submit-label="Delete contractor"
        />
    @endif
</div>

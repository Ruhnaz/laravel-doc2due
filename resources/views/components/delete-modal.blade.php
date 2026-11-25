@props([
    'show' => 'deleting',
    'title' => 'Delete confirmation',
    'action' => null,
    'formId' => null,
    'onSubmit' => null,
    'submitLabel' => 'Delete',
    'description' => null,
    'xText' => null,
    'close' => null,
])

@php
    $closeExpr = $close ?? ($show === 'pending' ? 'pending = null' : ($show . ' = false'));
@endphp

<div x-cloak
     x-show="{{ $show }}"
     class="fixed inset-0 z-50 flex items-center justify-center bg-ink/40 px-4"
     @keydown.escape.window="{{ $closeExpr }}">
    <div class="w-full max-w-md max-w-[calc(100vw-2rem)] max-h-[85vh] overflow-y-auto rounded-panel bg-surface p-6 text-left shadow-dialog"
         @click.outside="{{ $closeExpr }}">
        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-danger-soft text-danger">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M9 7V5h6v2M8 7l1 12h6l1-12"/></svg>
        </span>
        <h2 class="mt-4 text-lg font-semibold text-ink">{{ $title }}</h2>

        @if ($xText)
            <p class="mt-2 text-sm text-muted" x-text="{{ $xText }}"></p>
        @elseif ($description)
            <p class="mt-2 text-sm text-muted">{{ $description }}</p>
        @else
            <div class="mt-2 text-sm text-muted">{{ $slot }}</div>
        @endif

        @if ($action)
            <form @if ($formId) id="{{ $formId }}" @endif method="POST" action="{{ $action }}" class="mt-6">
                @csrf
                @method('DELETE')
                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <x-button type="button" variant="secondary" class="w-full sm:w-auto" @click="{{ $closeExpr }}">Cancel</x-button>
                    <x-button variant="danger" class="w-full sm:w-auto">{{ $submitLabel }}</x-button>
                </div>
            </form>
        @else
            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <x-button type="button" variant="secondary" class="w-full sm:w-auto" @click="{{ $closeExpr }}">Cancel</x-button>
                <x-button type="button" variant="danger" class="w-full sm:w-auto" @click="{{ $onSubmit }}">{{ $submitLabel }}</x-button>
            </div>
        @endif
    </div>
</div>

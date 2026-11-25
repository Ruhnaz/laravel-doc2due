@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
])

<div class="space-y-1.5">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-ink">{{ $label }}@if ($attributes->has('required'))<span class="text-danger"> *</span>@endif</label>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ $type === 'password' ? '' : old($name, $value) }}"
        {{ $attributes->merge(['class' => 'block w-full min-h-[44px] rounded-xl border-line text-sm text-ink shadow-sm placeholder:text-muted focus:border-brand focus:ring-brand/20']) }}
    />

    @error($name)
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

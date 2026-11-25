@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full border-l-4 border-brand bg-brand-soft py-2 pe-4 ps-3 text-start text-base font-medium text-brand focus:outline-none'
            : 'block w-full border-l-4 border-transparent py-2 pe-4 ps-3 text-start text-base font-medium text-muted hover:bg-brand-soft hover:text-ink focus:outline-none';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

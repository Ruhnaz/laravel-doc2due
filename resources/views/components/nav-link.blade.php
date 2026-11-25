@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex h-16 items-center border-b-2 border-brand px-1 text-sm font-medium text-brand focus:outline-none'
            : 'inline-flex h-16 items-center border-b-2 border-transparent px-1 text-sm font-medium text-muted hover:text-ink focus:outline-none';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

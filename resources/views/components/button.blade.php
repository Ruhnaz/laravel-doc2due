@props([
    'variant' => 'primary',
    'type' => 'submit',
    'href' => null,
    'size' => 'md',
])

@php
    $variants = [
        'primary' => 'rounded-xl bg-brand text-white shadow-sm hover:bg-brand-dark',
        'secondary' => 'rounded-xl border border-line bg-white text-ink hover:bg-page',
        'danger' => 'rounded-xl bg-danger text-white hover:bg-danger-dark',
        'ghost' => 'rounded-xl text-muted hover:bg-brand-soft',
    ];

    $sizes = [
        'sm' => 'min-h-[44px] px-3.5 py-2 text-sm',
        'md' => 'min-h-[44px] px-4 py-2.5 text-sm',
    ];

    $classes = 'inline-flex items-center justify-center gap-2 font-semibold transition focus:outline-none focus:ring-2 focus:ring-brand/30 disabled:cursor-not-allowed disabled:opacity-60 '
        .($variants[$variant] ?? $variants['primary']).' '
        .($sizes[$size] ?? $sizes['md']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif

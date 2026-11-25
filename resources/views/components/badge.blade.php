@props(['variant' => 'neutral'])

@php
    $styles = [
        'expired' => 'bg-status-expired-soft text-status-expired-text',
        'soon' => 'bg-status-soon-soft text-status-soon-text',
        'warning' => 'bg-status-month-soft text-status-month-text',
        'safe' => 'bg-status-valid-soft text-status-valid-text',
        'admin' => 'bg-brand-soft text-brand',
        'employee' => 'bg-page text-muted',
        'neutral' => 'bg-page text-muted',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium '.($styles[$variant] ?? $styles['neutral'])]) }}>
    {{ $slot }}
</span>

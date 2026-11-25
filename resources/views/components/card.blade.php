@props(['padding' => true])

<div {{ $attributes->merge(['class' => 'overflow-visible rounded-2xl border border-line bg-white shadow-card']) }}>
    @isset($header)
        <div class="border-b border-slate-100 px-4 py-4 sm:px-6 sm:py-5 md:px-8">
            {{ $header }}
        </div>
    @endisset

    <div @class(['px-4 py-5 sm:px-6 md:px-8 md:py-6' => $padding])>
        {{ $slot }}
    </div>
</div>

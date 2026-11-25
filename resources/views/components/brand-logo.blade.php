@props(['icon' => 'check', 'stroke' => 1.8, 'tagline' => null])

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 font-bold text-ink']) }}>
    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand text-white">
        <x-icon :name="$icon" :stroke="$stroke" />
    </span>
    <span class="flex flex-col leading-tight">
        <span>Doc2Due</span>
        @if ($tagline)
            <span class="hidden text-[10px] font-medium text-muted xl:block">{{ $tagline }}</span>
        @endif
    </span>
</span>

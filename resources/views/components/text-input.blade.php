@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'min-h-[44px] rounded-xl border-line text-sm text-ink shadow-sm focus:border-brand focus:ring-brand-soft']) }}>

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex min-h-[44px] items-center justify-center rounded-xl bg-brand px-5 py-2.5 text-sm font-semibold uppercase tracking-wide text-white shadow-sm hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-brand/30 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>

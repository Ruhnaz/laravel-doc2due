@props(['action', 'categories' => null, 'contractors' => null, 'viewAll' => false])

@php
    $filters = request()->only(['search', 'status', 'category', 'contractor']);
@endphp

<form method="GET" action="{{ $action }}" class="flex flex-col gap-3 border-b border-line px-4 py-4 sm:px-6 lg:flex-row lg:flex-wrap lg:items-center">
    <input
        type="search"
        name="search"
        value="{{ request('search') }}"
        placeholder="Search title or contractor"
        class="w-full min-h-[44px] rounded-xl border-line text-sm text-ink shadow-sm placeholder:text-muted focus:border-brand focus:ring-brand/20 lg:max-w-xs"
    />
    <select name="status" class="w-full min-h-[44px] rounded-xl border-line text-sm text-ink shadow-sm focus:border-brand focus:ring-brand/20 lg:w-auto" onchange="this.form.submit()">
        <option value="">All</option>
        @foreach (['expired' => 'Expired', 'due' => '0-30 days', 'soon' => '0-7 days', 'warning' => '8-30 days', 'safe' => '31+ days'] as $value => $label)
            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
        @endforeach
    </select>
    @if ($categories)
        <select name="category" class="w-full min-h-[44px] rounded-xl border-line text-sm text-ink shadow-sm focus:border-brand focus:ring-brand/20 lg:w-auto" onchange="this.form.submit()">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    @endif
    @if ($contractors)
        <select name="contractor" class="w-full min-h-[44px] rounded-xl border-line text-sm text-ink shadow-sm focus:border-brand focus:ring-brand/20 lg:w-auto" onchange="this.form.submit()">
            <option value="">All contractors</option>
            @foreach ($contractors as $contractor)
                <option value="{{ $contractor->id }}" @selected((string) request('contractor') === (string) $contractor->id)>{{ $contractor->name }}</option>
            @endforeach
        </select>
    @endif
    <div class="flex flex-wrap items-center gap-3">
        <x-button type="submit" size="sm">Search</x-button>
        <a href="{{ $action }}" class="inline-flex min-h-[44px] items-center px-2 text-sm font-semibold text-brand">Clear</a>
        <a href="{{ route('documents.export', $filters) }}" class="inline-flex min-h-[44px] min-w-[44px] items-center justify-center rounded-lg text-brand hover:bg-brand-soft lg:ml-auto" aria-label="Export CSV" title="Export CSV">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 4v9m0 0l-3.5-3.5M12 13l3.5-3.5M5 19h14" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        @if ($viewAll)
            <a href="{{ route('documents.index') }}" class="inline-flex min-h-[44px] items-center text-sm font-semibold text-brand">View all documents</a>
        @endif
    </div>
</form>

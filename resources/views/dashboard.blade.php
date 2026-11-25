<x-app-layout>
    <x-slot name="header">
        <div class="flex w-full flex-col gap-3 text-left sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-ink">{{ auth()->user()->company->name }}</h1>
                <p class="mt-1 text-sm text-muted">What needs attention before the next expiry</p>
            </div>
            <p class="text-sm font-semibold tabular-nums text-brand">{{ $usage }} / {{ $limit }} documents</p>
        </div>
    </x-slot>

    <div class="pb-10">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                @foreach ([
                    ['Total Documents', $total, route('documents.index'), 'text-ink', 'border-brand'],
                    ['Expiring Soon (0–30d)', $expiringSoon, route('documents.index', ['status' => 'due']), 'text-status-month-text', 'border-status-month'],
                    ['Expired', $counts['expired'], route('documents.index', ['status' => 'expired']), 'text-status-expired', 'border-status-expired'],
                    ['Contractors / Vendors', $contractors, route('contractors.index'), 'text-brand', 'border-brand-light'],
                ] as [$label, $value, $href, $tone, $accent])
                    <a href="{{ $href }}" class="rounded-card border border-line border-l-4 {{ $accent }} bg-surface p-4 shadow-card hover:bg-brand-tint">
                        <p class="text-sm font-medium text-muted">{{ $label }}</p>
                        <p class="mt-2 text-2xl sm:text-3xl font-semibold tabular-nums {{ $tone }}">{{ $value }}</p>
                    </a>
                @endforeach
            </div>

            <div class="grid gap-6 lg:grid-cols-12">
                <section class="rounded-card border border-line bg-surface shadow-card lg:col-span-7">
                    <div class="flex items-center justify-between gap-3 border-b border-line px-5 py-4">
                        <h2 class="text-lg font-semibold text-ink">Soonest expiring</h2>
                        <a href="{{ route('documents.index', request()->only(['search', 'status'])) }}" class="inline-flex min-h-[44px] items-center text-sm font-semibold text-brand">View all documents</a>
                    </div>
                    @if ($watch->isEmpty())
                        <p class="px-5 py-10 text-sm text-muted">{{ request()->filled('search') || request()->filled('status') ? 'No documents match your filters.' : 'No documents yet.' }}</p>
                    @else
                        <ul class="divide-y divide-line">
                            @foreach ($watch as $document)
                                @php
                                    $days = (int) now()->startOfDay()->diffInDays($document->expiry_date->copy()->startOfDay(), false);
                                @endphp
                                <li class="flex items-center gap-3 px-5 py-3">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate font-medium text-ink">{{ $document->title }}</p>
                                        <p class="mt-1 truncate text-xs text-muted">{{ $document->contractor->name ?? 'No contractor' }}</p>
                                    </div>
                                    <span class="hidden shrink-0 rounded-full bg-brand-soft px-2.5 py-1 text-xs font-medium text-brand sm:inline">{{ $document->category->name }}</span>
                                    <x-badge :variant="$document->status">{{ $days < 0 ? abs($days).'d over' : $days.'d' }}</x-badge>
                                    <a href="{{ route('documents.edit', $document) }}" class="inline-flex min-h-[44px] items-center shrink-0 text-sm font-semibold text-brand">Open</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>

                <div class="space-y-6 lg:col-span-5">
                    <section class="rounded-card border border-line bg-surface p-5 shadow-card">
                        <div class="flex items-start justify-between gap-3">
                            <h2 class="text-lg font-semibold text-ink">Notifications</h2>
                            <a href="{{ route('documents.index') }}" class="text-sm font-semibold text-brand">Documents</a>
                        </div>
                        <p class="mt-2 text-sm text-body">Daily reminders are on. Admins are emailed at 30, 15, and 7 days, then once after a document expires.</p>
                        <p class="mt-3 text-xs font-semibold uppercase tracking-wide text-muted">Last reminder</p>
                        <p class="mt-1 text-sm tabular-nums text-ink">{{ $alerts->first()?->sent_at?->format('M j, Y g:i A') ?? 'No reminders sent yet' }}</p>
                        @if ($alerts->isNotEmpty())
                            <ul class="mt-3 divide-y divide-line">
                                @foreach ($alerts as $alert)
                                    <li class="flex items-center justify-between gap-3 py-2 text-sm">
                                        <a href="{{ route('documents.edit', $alert->document) }}" class="truncate font-medium text-brand">{{ $alert->document->title }}</a>
                                        <span class="shrink-0 text-xs text-muted">{{ $alert->days_before === 0 ? 'Expired' : $alert->days_before.' days' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </section>

                    <section class="rounded-card border border-line bg-surface p-5 shadow-card">
                        <h2 class="text-lg font-semibold text-ink">Status</h2>
                        @php
                            $segments = [
                                ['Expired', $counts['expired'], 'bg-status-expired'],
                                ['0–7 days', $counts['soon'], 'bg-status-soon'],
                                ['8–30 days', $counts['warning'], 'bg-status-month'],
                                ['31+ days', $counts['safe'], 'bg-status-valid'],
                            ];
                            $barTotal = max(1, $total);
                        @endphp
                        <div class="mt-4 flex h-3 overflow-hidden rounded-full bg-page">
                            @foreach ($segments as [$label, $value, $color])
                                @if ($value > 0)
                                    <div class="{{ $color }}" style="width: {{ round($value / $barTotal * 100, 2) }}%"></div>
                                @endif
                            @endforeach
                        </div>
                        <ul class="mt-4 space-y-2 text-sm">
                            @foreach ($segments as [$label, $value, $color])
                                <li class="flex items-center justify-between gap-3">
                                    <span class="flex items-center gap-2 text-body"><span class="h-2.5 w-2.5 rounded-full {{ $color }}"></span>{{ $label }}</span>
                                    <span class="font-semibold tabular-nums text-ink">{{ $value }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </section>

                    <section class="rounded-card border border-line bg-surface shadow-card">
                        <div class="border-b border-line px-5 py-4">
                            <h2 class="text-lg font-semibold text-ink">Recent reminder & activity</h2>
                        </div>
                        @if ($feed->isEmpty())
                            <p class="px-5 py-8 text-sm text-muted">No reminders or uploads yet.</p>
                        @else
                            <ul class="divide-y divide-line">
                                @foreach ($feed as $item)
                                    <li class="px-5 py-3">
                                        <p class="truncate text-sm font-medium text-ink">{{ $item['title'] }}</p>
                                        <p class="mt-1 flex items-center justify-between gap-3 text-xs text-muted">
                                            <span>{{ $item['detail'] }}</span>
                                            <span class="tabular-nums">{{ $item['at']?->format('M j, Y') }}</span>
                                        </p>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

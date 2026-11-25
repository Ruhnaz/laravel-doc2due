<x-app-layout>
    <x-slot name="header">
        <div class="flex w-full flex-col gap-3 text-left sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-ink">Contractors</h1>
                <p class="mt-1 text-sm text-muted">The people and companies whose documents you track</p>
            </div>
            <x-button :href="route('contractors.create')" class="w-full sm:w-auto">+ Add contractor</x-button>
        </div>
    </x-slot>
    <div class="pb-10">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            @php
                $contractors->load('documents');
                $withExpired = $contractors->filter(fn ($contractor) => $contractor->documents->contains(fn ($document) => $document->status === 'expired'))->count();
                $expiringSoon = $contractors->filter(fn ($contractor) => $contractor->documents->contains(fn ($document) => in_array($document->status, ['soon', 'warning'], true)))->count();
                $allValid = $contractors->filter(fn ($contractor) => $contractor->documents->isNotEmpty() && $contractor->documents->every(fn ($document) => $document->status === 'safe'))->count();
            @endphp
            <x-card>
                <div class="grid grid-cols-2 gap-4 p-2 sm:p-4 lg:grid-cols-4 lg:gap-0 lg:divide-x lg:divide-line lg:p-0">
                    <div class="px-3 py-2 sm:px-4"><p class="text-sm text-muted">Total contractors</p><p class="mt-1 text-2xl sm:text-3xl font-semibold text-ink">{{ $contractors->count() }}</p></div>
                    <div class="px-3 py-2 sm:px-4"><p class="text-sm text-muted">With expired documents</p><p class="mt-1 text-2xl sm:text-3xl font-semibold text-status-expired">{{ $withExpired }}</p></div>
                    <div class="px-3 py-2 sm:px-4"><p class="text-sm text-muted">Expiring in 30 days</p><p class="mt-1 text-2xl sm:text-3xl font-semibold text-status-soon">{{ $expiringSoon }}</p></div>
                    <div class="px-3 py-2 sm:px-4"><p class="text-sm text-muted">All documents valid</p><p class="mt-1 text-2xl sm:text-3xl font-semibold text-status-valid">{{ $allValid }}</p></div>
                </div>
            </x-card>
            <x-card :padding="false">
                @if ($contractors->isEmpty())
                    <p class="px-6 py-10 text-sm text-muted">No contractors yet.</p>
                @else
                    <!-- Mobile stacked cards below md -->
                    <div class="divide-y divide-line md:hidden">
                        @foreach ($contractors as $contractor)
                            @php($next = $contractor->documents->sortBy('expiry_date')->first())
                            <div class="p-4 space-y-3 hover:bg-brand-tint" x-data="{ open: false, ask: false }">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0 flex-1">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-soft text-xs font-semibold text-brand">{{ strtoupper(collect(preg_split('/\s+/', trim($contractor->name)))->filter()->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('')) }}</span>
                                        <div class="min-w-0 flex-1">
                                            <a href="{{ route('contractors.edit', $contractor) }}" class="font-semibold text-brand text-base block truncate">{{ $contractor->name }}</a>
                                            <p class="text-xs text-muted truncate mt-0.5">{{ $contractor->email }}</p>
                                        </div>
                                    </div>
                                    <div class="relative flex items-center gap-2 shrink-0">
                                        @if ($next)
                                            <x-badge :variant="$next->status">{{ $next->statusLabel() }}</x-badge>
                                        @endif
                                        <div class="relative">
                                            <button type="button" class="flex h-11 w-11 items-center justify-center rounded-lg text-lg leading-none text-muted hover:bg-page focus:outline-none" @click="open = !open" aria-label="Row actions">⋮</button>
                                            <div x-cloak x-show="open" @click.outside="open = false" class="row-menu absolute right-0 z-30 mt-1 w-48 max-w-[calc(100vw-2rem)] max-h-[80vh] overflow-y-auto rounded-card border border-line bg-surface py-1 text-left shadow-menu">
                                                <a href="{{ route('contractors.edit', $contractor) }}" class="flex min-h-[44px] items-center gap-2 px-3 py-2 text-sm text-ink hover:bg-brand-tint"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 20h4l10-10-4-4L4 16v4z"/></svg>Edit</a>
                                                <button type="button" class="flex min-h-[44px] w-full items-center gap-2 px-3 py-2 text-left text-sm text-danger hover:bg-danger-soft" @click="ask = true; open = false"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M9 7V5h6v2M8 7l1 12h6l1-12"/></svg>Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-1 pt-1 text-sm text-body">
                                    <div class="flex items-center justify-between text-sm py-0.5">
                                        <span class="text-muted">Phone:</span>
                                        <span class="font-medium text-ink truncate ml-2">{{ $contractor->phone ?: '—' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm py-0.5">
                                        <span class="text-muted">Documents:</span>
                                        <span class="font-medium text-ink truncate ml-2">{{ $contractor->documents->count() }} documents</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm py-0.5">
                                        <span class="text-muted">Next expiry:</span>
                                        <span class="font-medium tabular-nums text-ink ml-2">{{ $next ? $next->expiry_date->format('M j, Y') : '—' }}</span>
                                    </div>
                                </div>
                                <x-delete-modal
                                    show="ask"
                                    :action="route('contractors.destroy', $contractor)"
                                    title="Delete this contractor?"
                                    :description="$contractor->name . ' will be removed. Documents linked to them stay in your company.'"
                                    submit-label="Delete contractor"
                                />
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop table md and up -->
                    <div class="admin-table hidden md:block overflow-visible">
                        <table class="w-full text-left text-sm">
                            <thead class="text-muted">
                                <tr class="border-b border-line">
                                    <th class="px-6 py-3 font-medium">Contractor</th>
                                    <th class="px-6 py-3 font-medium">Phone</th>
                                    <th class="px-6 py-3 font-medium">Documents</th>
                                    <th class="px-6 py-3 font-medium">Next expiry</th>
                                    <th class="px-6 py-3 font-medium">Status</th>
                                    <th class="px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line">
                                @foreach ($contractors as $contractor)
                                    @php($next = $contractor->documents->sortBy('expiry_date')->first())
                                    <tr class="hover:bg-brand-tint">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-soft text-xs font-semibold text-brand">{{ strtoupper(collect(preg_split('/\s+/', trim($contractor->name)))->filter()->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('')) }}</span>
                                                <div>
                                                    <a href="{{ route('contractors.edit', $contractor) }}" class="font-medium text-brand">{{ $contractor->name }}</a>
                                                    <p class="text-xs text-muted">{{ $contractor->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-body">{{ $contractor->phone ?: '—' }}</td>
                                        <td class="px-6 py-4 text-body">{{ $contractor->documents->count() }} documents</td>
                                        <td class="px-6 py-4 text-body">{{ $next ? $next->expiry_date->format('M j, Y') : '—' }}</td>
                                        <td class="px-6 py-4">@if ($next)<x-badge :variant="$next->status">{{ $next->statusLabel() }}</x-badge>@else — @endif</td>
                                        <td class="row-actions relative overflow-visible px-6 py-4 text-right" x-data="{ open: false, ask: false }">
                                            <button type="button" class="inline-flex min-h-[44px] min-w-[44px] items-center justify-center rounded-lg text-lg leading-none text-muted hover:bg-page focus:outline-none" @click="open = !open" aria-label="Row actions">⋮</button>
                                            <div x-cloak x-show="open" @click.outside="open = false" class="row-menu absolute right-6 z-30 mt-1 w-48 max-w-[calc(100vw-2rem)] max-h-[80vh] overflow-y-auto rounded-card border border-line bg-surface py-1 text-left shadow-menu">
                                                <a href="{{ route('contractors.edit', $contractor) }}" class="flex min-h-[44px] items-center gap-2 px-3 py-2 text-sm text-ink hover:bg-brand-tint"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 20h4l10-10-4-4L4 16v4z"/></svg>Edit</a>
                                                <button type="button" class="flex min-h-[44px] w-full items-center gap-2 px-3 py-2 text-left text-sm text-danger hover:bg-danger-soft" @click="ask = true; open = false"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M9 7V5h6v2M8 7l1 12h6l1-12"/></svg>Delete</button>
                                            </div>
                                            <x-delete-modal
                                                show="ask"
                                                :action="route('contractors.destroy', $contractor)"
                                                title="Delete this contractor?"
                                                :description="$contractor->name . ' will be removed. Documents linked to them stay in your company.'"
                                                submit-label="Delete contractor"
                                            />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        @php
            $account = auth()->user()->company->load('plan');
            $register = $account->documents;
        @endphp
        <div class="flex w-full flex-col gap-3 text-left sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-ink">Documents</h1>
                <p class="mt-1 text-sm text-muted">Manage and track all your company documents</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-4">
                <p class="text-sm text-ink">{{ $account->plan->name }} plan <span class="ml-2 font-semibold text-brand sm:ml-3">{{ $register->count() }} / {{ $account->plan->document_limit }} documents</span></p>
                <x-button :href="route('documents.create')" class="w-full sm:w-auto">+ Add document</x-button>
            </div>
        </div>
    </x-slot>
    <div class="pb-10" x-data="{ menu: null, pending: null }">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <x-card class="mb-6">
                <div class="grid grid-cols-2 gap-4 p-2 sm:p-4 lg:grid-cols-4 lg:gap-0 lg:divide-x lg:divide-line lg:p-0">
                    <div class="px-3 py-2 sm:px-4"><p class="text-sm text-muted">Expired</p><p class="mt-1 text-2xl sm:text-3xl font-semibold text-status-expired">{{ $register->where('status', 'expired')->count() }}</p></div>
                    <div class="px-3 py-2 sm:px-4"><p class="text-sm text-muted">0-7 days</p><p class="mt-1 text-2xl sm:text-3xl font-semibold text-status-soon">{{ $register->where('status', 'soon')->count() }}</p></div>
                    <div class="px-3 py-2 sm:px-4"><p class="text-sm text-muted">8-30 days</p><p class="mt-1 text-2xl sm:text-3xl font-semibold text-status-month">{{ $register->where('status', 'warning')->count() }}</p></div>
                    <div class="px-3 py-2 sm:px-4"><p class="text-sm text-muted">31+ days</p><p class="mt-1 text-2xl sm:text-3xl font-semibold text-status-valid">{{ $register->where('status', 'safe')->count() }}</p></div>
                </div>
            </x-card>
            <x-card :padding="false">
                <x-document-toolbar :action="route('documents.index')" :categories="$categories" :contractors="$contractors" />
                @if ($documents->isEmpty())
                    <p class="px-6 py-10 text-sm text-muted">{{ request()->filled('search') || request()->filled('status') || request()->filled('category') || request()->filled('contractor') ? 'No documents match your filters.' : 'No documents yet.' }}</p>
                    <p class="px-6 pb-4 text-sm text-muted">Showing {{ $documents->firstItem() ?? 0 }} to {{ $documents->lastItem() ?? 0 }} of {{ $documents->total() }}</p>
                @else
                    <!-- Mobile stacked cards below md -->
                    <div class="divide-y divide-line md:hidden">
                        @foreach ($documents as $document)
                            <div class="p-4 space-y-3 hover:bg-brand-tint">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <a href="{{ route('documents.edit', $document) }}" class="font-semibold text-brand text-base block truncate">{{ $document->title }}</a>
                                        <p class="text-xs uppercase text-muted mt-0.5">{{ pathinfo($document->file_name, PATHINFO_EXTENSION) }}</p>
                                    </div>
                                    <div class="relative flex items-center gap-2 shrink-0">
                                        <x-badge :variant="$document->status">{{ $document->statusLabel() }}</x-badge>
                                        <div class="relative">
                                            <button type="button" class="flex h-11 w-11 items-center justify-center rounded-lg text-lg leading-none text-muted hover:bg-page focus:outline-none" @click="menu = menu === {{ $document->id }} ? null : {{ $document->id }}" aria-label="Row actions">⋮</button>
                                            <div x-cloak x-show="menu === {{ $document->id }}" @click.outside="menu = null" class="row-menu absolute right-0 z-30 mt-1 w-48 max-w-[calc(100vw-2rem)] max-h-[80vh] overflow-y-auto rounded-card border border-line bg-surface py-1 text-left shadow-menu">
                                                <a href="{{ route('documents.edit', $document) }}" class="flex min-h-[44px] items-center gap-2 px-3 py-2 text-sm text-ink hover:bg-brand-tint"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 20h4l10-10-4-4L4 16v4z"/></svg>Edit</a>
                                                <a href="{{ route('documents.download', $document) }}" class="flex min-h-[44px] items-center gap-2 px-3 py-2 text-sm text-ink hover:bg-brand-tint"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 4v10m0 0l-4-4m4 4l4-4M5 18h14"/></svg>Download</a>
                                                <button type="button" class="flex min-h-[44px] w-full items-center gap-2 px-3 py-2 text-left text-sm text-danger hover:bg-danger-soft" @click="pending = { id: {{ $document->id }}, title: @js($document->title) }; menu = null"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M9 7V5h6v2M8 7l1 12h6l1-12"/></svg>Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-1 pt-1 text-sm text-body">
                                    <div class="flex items-center justify-between text-sm py-0.5">
                                        <span class="text-muted">Contractor:</span>
                                        <span class="font-medium text-ink truncate ml-2">{{ $document->contractor->name ?? '—' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm py-0.5">
                                        <span class="text-muted">Category:</span>
                                        <span class="font-medium text-ink truncate ml-2">{{ $document->category->name }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm py-0.5">
                                        <span class="text-muted">Expiry date:</span>
                                        <span class="font-medium tabular-nums text-ink ml-2">{{ $document->expiry_date->format('M j, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop table md and up -->
                    <div class="admin-table hidden md:block overflow-visible">
                        <table class="w-full text-left text-sm">
                            <thead class="text-muted">
                                <tr class="border-b border-line">
                                    <th class="px-6 py-3 font-medium">Title</th>
                                    <th class="px-6 py-3 font-medium">Contractor</th>
                                    <th class="px-6 py-3 font-medium">Category</th>
                                    <th class="px-6 py-3 font-medium">Expiry date</th>
                                    <th class="px-6 py-3 font-medium">Status</th>
                                    <th class="px-6 py-3 font-medium"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line">
                                @foreach ($documents as $document)
                                    <tr class="hover:bg-brand-tint">
                                        <td class="px-6 py-4">
                                            <a href="{{ route('documents.edit', $document) }}" class="font-medium text-brand">{{ $document->title }}</a>
                                            <p class="text-xs uppercase text-muted">{{ pathinfo($document->file_name, PATHINFO_EXTENSION) }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-body">{{ $document->contractor->name ?? '—' }}</td>
                                        <td class="px-6 py-4 text-body">{{ $document->category->name }}</td>
                                        <td class="px-6 py-4 tabular-nums text-body">{{ $document->expiry_date->format('M j, Y') }}</td>
                                        <td class="px-6 py-4"><x-badge :variant="$document->status">{{ $document->statusLabel() }}</x-badge></td>
                                        <td class="row-actions relative overflow-visible px-6 py-4 text-right">
                                            <button type="button" class="inline-flex min-h-[44px] min-w-[44px] items-center justify-center rounded-lg text-lg leading-none text-muted hover:bg-page focus:outline-none" @click="menu = menu === {{ $document->id }} ? null : {{ $document->id }}" aria-label="Row actions">⋮</button>
                                            <div x-cloak x-show="menu === {{ $document->id }}" @click.outside="menu = null" class="row-menu absolute right-6 z-30 mt-1 w-48 max-w-[calc(100vw-2rem)] max-h-[80vh] overflow-y-auto rounded-card border border-line bg-surface py-1 text-left shadow-menu">
                                                <a href="{{ route('documents.edit', $document) }}" class="flex min-h-[44px] items-center gap-2 px-3 py-2 text-sm text-ink hover:bg-brand-tint"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 20h4l10-10-4-4L4 16v4z"/></svg>Edit</a>
                                                <a href="{{ route('documents.download', $document) }}" class="flex min-h-[44px] items-center gap-2 px-3 py-2 text-sm text-ink hover:bg-brand-tint"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 4v10m0 0l-4-4m4 4l4-4M5 18h14"/></svg>Download</a>
                                                <button type="button" class="flex min-h-[44px] w-full items-center gap-2 px-3 py-2 text-left text-sm text-danger hover:bg-danger-soft" @click="pending = { id: {{ $document->id }}, title: @js($document->title) }; menu = null"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M9 7V5h6v2M8 7l1 12h6l1-12"/></svg>Delete</button>
                                            </div>
                                            <form id="delete-document-{{ $document->id }}" method="POST" action="{{ route('documents.destroy', $document) }}" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="flex flex-col gap-3 border-t border-line sm:flex-row sm:items-center sm:justify-between">
                        <p class="px-6 pt-4 text-sm text-muted sm:py-4">Showing {{ $documents->firstItem() ?? 0 }} to {{ $documents->lastItem() ?? 0 }} of {{ $documents->total() }}</p>
                        {{ $documents->links('pagination.brand') }}
                    </div>
                @endif
            </x-card>
        </div>

        <x-delete-modal
            show="pending"
            title="Delete this document?"
            x-text="pending ? pending.title + ' and its uploaded file will be permanently removed. This cannot be undone.' : ''"
            on-submit="document.getElementById('delete-document-' + pending.id).submit()"
            submit-label="Delete document"
        />
    </div>
</x-app-layout>

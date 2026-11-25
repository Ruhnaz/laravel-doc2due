<x-app-layout>
    <x-slot name="header">
        <div class="flex w-full flex-col gap-3 text-left sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-ink">Categories</h1>
                <p class="mt-1 text-sm text-muted">Group your documents so they are easy to find</p>
            </div>
            <x-button :href="route('categories.create')" class="w-full sm:w-auto">+ Add category</x-button>
        </div>
    </x-slot>
    <div class="pb-10" x-data="{ menu: null, pending: null }">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-6 rounded-2xl bg-danger-soft px-4 py-3 text-sm text-danger">{{ session('error') }}</div>
            @endif
            <x-card :padding="false">
                @if ($categories->isEmpty())
                    <p class="px-6 py-10 text-sm text-muted">No categories yet.</p>
                @else
                    <!-- Mobile stacked cards below md -->
                    <div class="divide-y divide-line md:hidden">
                        @foreach ($categories as $category)
                            <div class="p-4 space-y-2 hover:bg-brand-tint">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <a href="{{ route('categories.edit', $category) }}" class="font-semibold text-brand text-base block truncate">{{ $category->name }}</a>
                                    </div>
                                    <div class="relative shrink-0">
                                        <button type="button" class="flex h-11 w-11 items-center justify-center rounded-lg text-lg leading-none text-muted hover:bg-page focus:outline-none" @click="menu = menu === {{ $category->id }} ? null : {{ $category->id }}" aria-label="Row actions">⋮</button>
                                        <div x-cloak x-show="menu === {{ $category->id }}" @click.outside="menu = null" class="row-menu absolute right-0 z-30 mt-1 w-48 max-w-[calc(100vw-2rem)] max-h-[80vh] overflow-y-auto rounded-card border border-line bg-surface py-1 text-left shadow-menu">
                                            <a href="{{ route('categories.edit', $category) }}" class="flex min-h-[44px] items-center gap-2 px-3 py-2 text-sm text-ink hover:bg-brand-tint"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 20h4l10-10-4-4L4 16v4z"/></svg>Edit</a>
                                            <button type="button" class="flex min-h-[44px] w-full items-center gap-2 px-3 py-2 text-left text-sm text-danger hover:bg-danger-soft" @click="pending = { id: {{ $category->id }}, name: @js($category->name) }; menu = null"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M9 7V5h6v2M8 7l1 12h6l1-12"/></svg>Delete</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-sm text-muted">
                                    <span class="font-medium text-body">{{ $category->documents()->count() }} documents</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop table md and up -->
                    <div class="admin-table hidden md:block overflow-visible">
                        <table class="w-full text-left text-sm">
                            <thead class="text-muted">
                                <tr class="border-b border-line">
                                    <th class="px-6 py-3 font-medium">Category</th>
                                    <th class="px-6 py-3 font-medium">Documents</th>
                                    <th class="px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line">
                                @foreach ($categories as $category)
                                    <tr class="hover:bg-brand-tint">
                                        <td class="px-6 py-4 font-medium text-brand">{{ $category->name }}</td>
                                        <td class="px-6 py-4 text-body">{{ $category->documents()->count() }} documents</td>
                                        <td class="row-actions relative overflow-visible px-6 py-4 text-right">
                                            <button type="button" class="inline-flex min-h-[44px] min-w-[44px] items-center justify-center rounded-lg text-lg leading-none text-muted hover:bg-page focus:outline-none" @click="menu = menu === {{ $category->id }} ? null : {{ $category->id }}" aria-label="Row actions">⋮</button>
                                            <div x-cloak x-show="menu === {{ $category->id }}" @click.outside="menu = null" class="row-menu absolute right-6 z-30 mt-1 w-48 max-w-[calc(100vw-2rem)] max-h-[80vh] overflow-y-auto rounded-card border border-line bg-surface py-1 text-left shadow-menu">
                                                <a href="{{ route('categories.edit', $category) }}" class="flex min-h-[44px] items-center gap-2 px-3 py-2 text-sm text-ink hover:bg-brand-tint"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 20h4l10-10-4-4L4 16v4z"/></svg>Edit</a>
                                                <button type="button" class="flex min-h-[44px] w-full items-center gap-2 px-3 py-2 text-left text-sm text-danger hover:bg-danger-soft" @click="pending = { id: {{ $category->id }}, name: @js($category->name) }; menu = null"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M9 7V5h6v2M8 7l1 12h6l1-12"/></svg>Delete</button>
                                            </div>
                                            <form id="delete-category-{{ $category->id }}" method="POST" action="{{ route('categories.destroy', $category) }}" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-card>
        </div>
        <x-delete-modal
            show="pending"
            title="Delete this category?"
            x-text="pending ? pending.name + ' will be removed. Documents in it stay in your company only if the category can be removed.' : ''"
            on-submit="document.getElementById('delete-category-' + pending.id).submit()"
            submit-label="Delete category"
        />
    </div>
</x-app-layout>

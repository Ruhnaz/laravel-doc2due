<x-app-layout>
    <x-slot name="header">
        <div class="flex w-full flex-col gap-3 text-left sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-ink">Employees</h1>
                <p class="mt-1 text-sm text-muted">People who can access {{ auth()->user()->company->name }}</p>
            </div>
            <x-button :href="route('employees.create')" class="w-full sm:w-auto">+ Add employee</x-button>
        </div>
    </x-slot>
    <div class="pb-10">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <x-card :padding="false">
                @if ($employees->isEmpty())
                    <p class="px-6 py-10 text-sm text-muted">No employees yet.</p>
                @else
                    <!-- Mobile stacked cards below md -->
                    <div class="divide-y divide-line md:hidden">
                        @foreach ($employees as $employee)
                            <div class="p-4 space-y-3 hover:bg-brand-tint" x-data="{ open: false, ask: false }">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0 flex-1">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-soft text-xs font-semibold text-brand">{{ strtoupper(collect(preg_split('/\s+/', trim($employee->name)))->filter()->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('')) }}</span>
                                        <div class="min-w-0 flex-1">
                                            <a href="{{ route('employees.edit', $employee) }}" class="font-semibold text-brand text-base block truncate">{{ $employee->name }}@if (auth()->user()->is($employee)) <span class="font-normal text-muted text-sm">(you)</span>@endif</a>
                                            <p class="text-xs text-muted truncate mt-0.5">{{ $employee->email }}</p>
                                        </div>
                                    </div>
                                    <div class="relative flex items-center gap-2 shrink-0">
                                        <x-badge :variant="$employee->isAdmin() ? 'admin' : 'employee'">{{ $employee->role->label() }}</x-badge>
                                        <div class="relative">
                                            <button type="button" class="flex h-11 w-11 items-center justify-center rounded-lg text-lg leading-none text-muted hover:bg-page focus:outline-none" @click="open = !open" aria-label="Row actions">⋮</button>
                                            <div x-cloak x-show="open" @click.outside="open = false" class="row-menu absolute right-0 z-30 mt-1 w-48 max-w-[calc(100vw-2rem)] max-h-[80vh] overflow-y-auto rounded-card border border-line bg-surface py-1 text-left shadow-menu">
                                                <a href="{{ route('employees.edit', $employee) }}" class="flex min-h-[44px] items-center gap-2 px-3 py-2 text-sm text-ink hover:bg-brand-tint"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 20h4l10-10-4-4L4 16v4z"/></svg>Edit</a>
                                                @if (auth()->user()->isNot($employee))
                                                    <button type="button" class="flex min-h-[44px] w-full items-center gap-2 px-3 py-2 text-left text-sm text-danger hover:bg-danger-soft" @click="ask = true; open = false"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M9 7V5h6v2M8 7l1 12h6l1-12"/></svg>Remove</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between text-sm py-0.5 pt-1">
                                    <span class="text-muted">Added:</span>
                                    <span class="font-medium text-body ml-2">{{ $employee->created_at->format('M j, Y') }}</span>
                                </div>
                                @if (auth()->user()->isNot($employee))
                                    <x-delete-modal
                                        show="ask"
                                        :action="route('employees.destroy', $employee)"
                                        title="Remove this employee?"
                                        :description="$employee->name . ' will lose access immediately. Documents they added stay in your company.'"
                                        submit-label="Remove employee"
                                    />
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop table md and up -->
                    <div class="admin-table hidden md:block overflow-visible">
                        <table class="w-full text-left text-sm">
                            <thead class="text-muted">
                                <tr class="border-b border-line">
                                    <th class="px-6 py-3 font-medium">Employee</th>
                                    <th class="px-6 py-3 font-medium">Role</th>
                                    <th class="px-6 py-3 font-medium">Added</th>
                                    <th class="px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line">
                                @foreach ($employees as $employee)
                                    <tr class="hover:bg-brand-tint">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-soft text-xs font-semibold text-brand">{{ strtoupper(collect(preg_split('/\s+/', trim($employee->name)))->filter()->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('')) }}</span>
                                                <div>
                                                    <a href="{{ route('employees.edit', $employee) }}" class="font-medium text-brand">{{ $employee->name }}@if (auth()->user()->is($employee)) <span class="font-normal text-muted">(you)</span>@endif</a>
                                                    <p class="text-xs text-muted">{{ $employee->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4"><x-badge :variant="$employee->isAdmin() ? 'admin' : 'employee'">{{ $employee->role->label() }}</x-badge></td>
                                        <td class="px-6 py-4 text-body">{{ $employee->created_at->format('M j, Y') }}</td>
                                        <td class="row-actions relative overflow-visible px-6 py-4 text-right" x-data="{ open: false, ask: false }">
                                            <button type="button" class="inline-flex min-h-[44px] min-w-[44px] items-center justify-center rounded-lg text-lg leading-none text-muted hover:bg-page focus:outline-none" @click="open = !open" aria-label="Row actions">⋮</button>
                                            <div x-cloak x-show="open" @click.outside="open = false" class="row-menu absolute right-6 z-30 mt-1 w-48 max-w-[calc(100vw-2rem)] max-h-[80vh] overflow-y-auto rounded-card border border-line bg-surface py-1 text-left shadow-menu">
                                                <a href="{{ route('employees.edit', $employee) }}" class="flex min-h-[44px] items-center gap-2 px-3 py-2 text-sm text-ink hover:bg-brand-tint"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 20h4l10-10-4-4L4 16v4z"/></svg>Edit</a>
                                                @if (auth()->user()->isNot($employee))
                                                    <button type="button" class="flex min-h-[44px] w-full items-center gap-2 px-3 py-2 text-left text-sm text-danger hover:bg-danger-soft" @click="ask = true; open = false"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M9 7V5h6v2M8 7l1 12h6l1-12"/></svg>Remove</button>
                                                @endif
                                            </div>
                                            @if (auth()->user()->isNot($employee))
                                                <x-delete-modal
                                                    show="ask"
                                                    :action="route('employees.destroy', $employee)"
                                                    title="Remove this employee?"
                                                    :description="$employee->name . ' will lose access immediately. Documents they added stay in your company.'"
                                                    submit-label="Remove employee"
                                                />
                                            @endif
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

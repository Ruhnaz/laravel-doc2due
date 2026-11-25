@php
    $links = [
        ['dashboard', 'dashboard', __('Dashboard'), 'dashboard'],
        ['documents.*', 'documents.index', __('Documents'), 'documents'],
        ['contractors.*', 'contractors.index', __('Contractors'), 'contractors'],
        ['categories.*', 'categories.index', __('Categories'), 'categories'],
    ];
    if (Auth::user()->isAdmin()) {
        $links[] = ['billing.*', 'billing.index', __('Billing'), 'billing'];
        $links[] = ['employees.*', 'employees.index', __('Employees'), 'employees'];
    }
    $initials = strtoupper(collect(preg_split('/\s+/', trim(Auth::user()->name)))->filter()->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode(''));
@endphp

<div x-data>
    <header class="nav-top h-16 border-b border-line bg-surface">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex min-w-0 items-center">
                <a href="{{ route('dashboard') }}"><x-brand-logo icon="brand" :stroke="2" /></a>
                <nav class="ms-8 hidden items-stretch gap-6 lg:flex">
                    @foreach ($links as [$match, $name, $label])
                        <a href="{{ route($name) }}" @class([
                            'inline-flex h-16 items-center border-b-2 px-1 text-sm font-medium',
                            'border-brand text-brand' => request()->routeIs($match),
                            'border-transparent text-muted hover:text-ink' => ! request()->routeIs($match),
                        ])>{{ $label }}</a>
                    @endforeach
                </nav>
            </div>
            <div class="flex items-center gap-2">
                @include('layouts.partials.layout-switcher')
                <div class="hidden lg:block">
                    @include('layouts.partials.user-menu')
                </div>
                <button type="button" class="flex h-11 w-11 items-center justify-center rounded-lg text-muted hover:text-ink hover:bg-page focus:outline-none lg:hidden" @click="$store.layout.open = true" aria-label="Open menu">
                    <x-icon name="menu" class="h-6 w-6" />
                </button>
            </div>
        </div>
    </header>

    <aside class="nav-side fixed inset-y-0 left-0 z-40 w-56 flex-col justify-between bg-brand text-white">
        <div>
            <a href="{{ route('dashboard') }}" class="flex h-16 items-center px-4 text-white [&_span]:text-white">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/15">
                    <x-icon name="brand" />
                </span>
                <span class="ms-2 font-bold">Doc2Due</span>
            </a>
            <nav class="flex flex-col gap-1 px-3">
                @foreach ($links as [$match, $name, $label, $icon])
                    <a href="{{ route($name) }}" @class([
                        'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm',
                        'bg-white/15 font-medium text-white' => request()->routeIs($match),
                        'text-white/80 hover:bg-white/10 hover:text-white' => ! request()->routeIs($match),
                    ])>
                        <x-icon :name="$icon" />
                        {{ $label }}
                    </a>
                @endforeach
            </nav>
        </div>
        <div class="mx-3 mb-3 rounded-xl bg-white/10 p-3">
            <div class="flex items-center justify-between gap-2 text-xs font-semibold text-white/80">
                <span>{{ $sidebarPlanName }} plan</span>
                <span>{{ $sidebarDocumentCount }} / {{ $sidebarDocumentLimit }}</span>
            </div>
            <div class="mt-2 h-1 overflow-hidden rounded-full bg-white/20">
                <div class="h-full rounded-full {{ $sidebarUsageTone }}" style="width: {{ $sidebarUsagePercent }}%"></div>
            </div>
            @if ($sidebarShowUpgrade)
                <a href="{{ route('billing.index') }}" class="mt-2 inline-flex text-xs font-semibold text-white underline underline-offset-2">Upgrade</a>
            @endif
        </div>
        <div class="border-t border-white/15 p-3">
            <div class="mb-3 flex justify-center rounded-xl bg-white/10 p-1">
                <button type="button" class="flex h-11 w-11 items-center justify-center rounded-lg text-white/70 hover:bg-white/10 hover:text-white" @click="$store.layout.set('compact')" aria-label="Compact sidebar" title="Compact sidebar">
                    <x-icon name="sidebar" />
                </button>
                <button type="button" class="flex h-11 w-11 items-center justify-center rounded-lg text-white/70 hover:bg-white/10 hover:text-white" @click="$store.layout.set('top')" aria-label="Top navigation" title="Top navigation">
                    <x-icon name="topnav" />
                </button>
            </div>
            <div class="flex items-center justify-between gap-2 rounded-lg px-2 py-1">
                <a href="{{ route('profile.edit') }}" class="flex min-w-0 items-center gap-2 text-sm text-white/80 hover:text-white">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white/15 text-xs font-semibold text-white">{{ $initials }}</span>
                    <span class="truncate">{{ Auth::user()->name }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                    @csrf
                    <button type="submit" class="flex h-11 w-11 items-center justify-center rounded-lg text-white/80 hover:bg-white/10 hover:text-white" aria-label="{{ __('Log Out') }}" title="{{ __('Log Out') }}">
                        <x-icon name="logout" />
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <aside class="nav-compact fixed inset-y-0 left-0 z-40 w-16 flex-col items-center justify-between bg-brand py-4 text-white">
        <div class="flex flex-col items-center gap-3">
            <a href="{{ route('dashboard') }}" class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/15 text-white" title="Doc2Due">
                <x-icon name="brand" />
            </a>
            <nav class="flex flex-col items-center gap-2">
                @foreach ($links as [$match, $name, $label, $icon])
                    <a href="{{ route($name) }}" title="{{ $label }}" @class([
                        'group relative rounded-lg p-2.5',
                        'bg-white/20 text-white' => request()->routeIs($match),
                        'text-white/75 hover:bg-white/10 hover:text-white' => ! request()->routeIs($match),
                    ])>
                        <x-icon :name="$icon" />
                        <span class="pointer-events-none absolute left-full z-50 ms-3 hidden whitespace-nowrap rounded-lg bg-ink px-2 py-1 text-xs text-white group-hover:block">{{ $label }}</span>
                    </a>
                @endforeach
            </nav>
        </div>
        <div class="flex w-full flex-col items-center gap-2 border-t border-white/15 pt-3">
            <button type="button" title="Expand sidebar" aria-label="Expand sidebar" class="rounded-lg p-2.5 text-white/70 hover:bg-white/10" @click="$store.layout.set('side')">
                <x-icon name="sidebar" />
            </button>
            <button type="button" title="Top navigation" class="rounded-lg p-2.5 text-white/70 hover:bg-white/10" @click="$store.layout.set('top')">
                <x-icon name="topnav" />
            </button>
            <a href="{{ route('profile.edit') }}" title="{{ __('Profile') }}" class="rounded-lg p-2.5 text-white/75 hover:bg-white/10 hover:text-white">
                <x-icon name="profile" />
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="{{ __('Log Out') }}" class="rounded-lg p-2.5 text-white/75 hover:bg-white/10 hover:text-white">
                    <x-icon name="logout" />
                </button>
            </form>
        </div>
    </aside>

    <div class="fixed inset-0 z-50 lg:hidden"
         x-show="$store.layout.open"
         @keydown.window.escape="$store.layout.open = false"
         x-cloak>
        <div class="fixed inset-0 bg-ink/40 transition-opacity"
             x-show="$store.layout.open"
             x-transition:enter="transition-opacity ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="$store.layout.open = false"
             aria-hidden="true"></div>

        <div class="fixed inset-y-0 left-0 flex w-72 max-w-[calc(100vw-2rem)] flex-col justify-between bg-surface shadow-dialog overflow-y-auto"
             x-show="$store.layout.open"
             x-transition:enter="transition transform ease-out duration-200"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition transform ease-in duration-150"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full">
            <div>
                <div class="flex h-16 items-center justify-between border-b border-line px-4">
                    <a href="{{ route('dashboard') }}" @click="$store.layout.open = false"><x-brand-logo icon="brand" :stroke="2" /></a>
                    <button type="button" class="flex h-11 w-11 items-center justify-center rounded-lg text-muted hover:text-ink hover:bg-page focus:outline-none" @click="$store.layout.open = false" aria-label="Close menu">
                        <x-icon name="close" class="h-6 w-6" />
                    </button>
                </div>
                <nav class="flex flex-col gap-1 p-3">
                    @foreach ($links as [$match, $name, $label, $icon])
                        <a href="{{ route($name) }}" @click="$store.layout.open = false" @class([
                            'flex min-h-[44px] items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium',
                            'bg-brand-soft text-brand' => request()->routeIs($match),
                            'text-body hover:bg-brand-tint hover:text-brand' => ! request()->routeIs($match),
                        ])>
                            <x-icon :name="$icon" />
                            {{ $label }}
                        </a>
                    @endforeach
                </nav>
            </div>
            <div class="border-t border-line p-4">
                <p class="font-medium text-ink">{{ Auth::user()->name }}</p>
                <p class="text-sm text-muted truncate">{{ Auth::user()->email }}</p>
                <a href="{{ route('profile.edit') }}" @click="$store.layout.open = false" class="mt-3 flex min-h-[44px] items-center gap-3 rounded-lg px-2 text-sm font-medium text-ink hover:bg-brand-tint">
                    <x-icon name="profile" class="text-muted" />
                    {{ __('Profile') }}
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit" @click="$store.layout.open = false" class="flex min-h-[44px] w-full items-center gap-3 rounded-lg px-2 text-sm font-medium text-ink hover:bg-brand-tint">
                        <x-icon name="logout" class="text-muted" />
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Doc2Due') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <script>
            (function () {
                var mode = 'compact';
                try {
                    var saved = localStorage.getItem('et-layout');
                    if (saved === 'top' || saved === 'side' || saved === 'compact') mode = saved;
                } catch (e) {}
                document.documentElement.setAttribute('data-layout', mode);
            })();
        </script>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            [x-cloak]{display:none !important}
            .nav-side,.nav-compact{display:none}
            @media (min-width:1024px){
                html[data-layout="side"] .nav-side{display:flex}
                html[data-layout="side"] .app-shell{padding-left:14rem}
                html:not([data-layout="top"]):not([data-layout="side"]) .nav-compact{display:flex}
                html:not([data-layout="top"]):not([data-layout="side"]) .app-shell{padding-left:4rem}
                html:not([data-layout="top"]) .nav-top{display:none}
            }
        </style>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('layout', {
                    mode: document.documentElement.getAttribute('data-layout') || 'compact',
                    open: false,
                    set(mode) {
                        this.mode = mode;
                        this.open = false;
                        document.documentElement.setAttribute('data-layout', mode);
                        localStorage.setItem('et-layout', mode);
                    },
                });
            });
        </script>
        <x-analytics />
    </head>
    <body class="bg-page text-body font-admin tabular-nums antialiased">
        <div class="app-shell min-h-screen">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-transparent">
                    <div class="mx-auto max-w-6xl px-4 py-6 text-left sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>

            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-cloak class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-[70] flex max-w-[calc(100vw-2rem)] items-center gap-2 rounded-xl bg-ink px-4 py-3 text-sm text-white shadow-menu">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12l5 5L20 7"/></svg>
                    <span class="truncate">{{ session('success') }}</span>
                </div>
            @endif
        </div>
    </body>
</html>

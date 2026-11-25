<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page not found | {{ config('app.name', 'Doc2Due') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <x-analytics />
</head>
<body class="min-h-screen bg-page font-landing text-body antialiased">
    <div class="flex min-h-screen flex-col px-6 py-6 sm:px-10 sm:py-8">
        <a href="{{ url('/') }}" class="inline-flex w-fit">
            <x-brand-logo icon="brand" :stroke="2" />
        </a>

        <main class="flex flex-1 flex-col items-center justify-center pb-16 text-center">
            <svg class="h-10 w-10 text-ink" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                <circle cx="16" cy="18" r="2.2" fill="currentColor"/>
                <circle cx="32" cy="18" r="2.2" fill="currentColor"/>
                <path d="M17 32c2.2-2.4 4.6-3.6 7-3.6s4.8 1.2 7 3.6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
            </svg>

            <h1 class="mt-10 text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">Oops!<br class="sm:hidden"> Page not found</h1>
            <p class="mt-4 max-w-md text-sm text-muted sm:text-base">The page you’re looking for doesn’t exist or may have been moved.</p>
            <a href="{{ url('/') }}" class="mt-8 inline-flex min-h-[44px] items-center justify-center rounded-[10px] bg-brand px-5 text-xs font-semibold uppercase tracking-wide text-white hover:bg-brand-dark">Go to homepage</a>
        </main>
    </div>
</body>
</html>

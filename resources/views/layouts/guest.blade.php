<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Doc2Due') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>[x-cloak]{display:none !important} h1,h2{letter-spacing:-0.025em}</style>
        <x-analytics />
    </head>
    <body class="bg-page text-body font-landing antialiased">
        <div class="grid min-h-screen lg:grid-cols-2">
            <div class="relative flex flex-col px-6 py-8 sm:px-12 lg:px-16">
                <a href="/" class="inline-flex">
                    <x-brand-logo icon="brand" :stroke="2" />
                </a>
                <div class="flex flex-1 items-center">
                    <div class="w-full max-w-md">
                        {{ $slot }}
                    </div>
                </div>
            </div>
            <div class="hidden items-center justify-center bg-brand-gradient px-10 lg:flex">
                <div class="mx-auto w-full max-w-xl text-white">
                    <h2 class="text-4xl font-bold leading-tight">Every document. Every expiry. One place.</h2>
                    <p class="mt-4 text-lg text-white/75">Track licenses, insurance and contracts, and get reminded before they lapse.</p>
                    <div class="hframe mt-8">
                        <div class="hwin">
                            <div class="dots" aria-hidden="true"><span></span><span></span><span></span></div>
                            <img src="{{ asset('images/landing/documents.png') }}" alt="Documents">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

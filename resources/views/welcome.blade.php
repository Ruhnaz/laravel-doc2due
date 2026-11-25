<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Doc2Due') }} | Never Miss an Expiry Date Again.</title>
    <meta name="description" content="Doc2Due helps teams track document expiry dates and get reminders before they lapse. Never Miss an Expiry Date Again.">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ filemtime(public_path('favicon.ico')) }}" sizes="32x32">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none !important} h1,h2{letter-spacing:-0.025em}</style>
    <x-analytics />
</head>
<body class="bg-page font-landing text-body antialiased">
    <header class="sticky top-0 z-50 border-b border-line bg-page/90 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-site items-center justify-between gap-6 px-4 sm:px-6">
            <a href="{{ url('/') }}" class="flex shrink-0 items-center">
                <x-brand-logo icon="brand" :stroke="2" tagline="Never Miss an Expiry Date Again." />
            </a>
            <nav class="hidden items-center gap-6 lg:flex">
                <a href="#problem" class="text-sm font-medium text-muted transition-colors hover:text-brand">Why Doc2Due</a>
                <a href="#who" class="text-sm font-medium text-muted transition-colors hover:text-brand">Who it's for</a>
                <a href="#features" class="text-sm font-medium text-muted transition-colors hover:text-brand">Features</a>
                <a href="#how" class="text-sm font-medium text-muted transition-colors hover:text-brand">How it works</a>
                <a href="#pricing" class="text-sm font-medium text-muted transition-colors hover:text-brand">Pricing</a>
                <a href="#faq" class="text-sm font-medium text-muted transition-colors hover:text-brand">FAQs</a>
            </nav>
            <div class="hidden items-center gap-3 lg:flex">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex h-[42px] items-center rounded-[10px] bg-brand px-5 text-sm font-semibold text-white hover:bg-brand-dark">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-ink hover:text-brand">Log in</a>
                    <a href="{{ route('register') }}" class="inline-flex h-[42px] items-center rounded-[10px] bg-brand px-5 text-sm font-semibold text-white hover:bg-brand-dark">Sign up free</a>
                @endauth
            </div>
            <details class="relative lg:hidden" x-data="{ open: false }" @toggle="open = $el.open">
                <summary class="flex h-11 w-11 cursor-pointer list-none items-center justify-center rounded-lg text-ink [&::-webkit-details-marker]:hidden" aria-label="Toggle menu">
                    <x-icon name="menu" :stroke="1.7" x-show="!open" />
                    <x-icon name="close" :stroke="1.7" x-show="open" />
                </summary>
                <div class="absolute right-0 top-full z-50 w-56 rounded-card border border-line bg-surface p-3 text-sm font-medium shadow-menu">
                    <a href="#problem" @click="$el.closest('details').open = false" class="block py-2 text-sm font-medium text-muted transition-colors hover:text-brand">Why Doc2Due</a>
                    <a href="#who" @click="$el.closest('details').open = false" class="block py-2 text-sm font-medium text-muted transition-colors hover:text-brand">Who it's for</a>
                    <a href="#features" @click="$el.closest('details').open = false" class="block py-2 text-sm font-medium text-muted transition-colors hover:text-brand">Features</a>
                    <a href="#how" @click="$el.closest('details').open = false" class="block py-2 text-sm font-medium text-muted transition-colors hover:text-brand">How it works</a>
                    <a href="#pricing" @click="$el.closest('details').open = false" class="block py-2 text-sm font-medium text-muted transition-colors hover:text-brand">Pricing</a>
                    <a href="#faq" @click="$el.closest('details').open = false" class="block py-2 text-sm font-medium text-muted transition-colors hover:text-brand">FAQs</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="mt-2 inline-flex h-[42px] w-full items-center justify-center rounded-[10px] bg-brand px-5 text-sm font-semibold text-white hover:bg-brand-dark">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="block py-2 text-sm font-medium text-ink hover:text-brand">Log in</a>
                        <a href="{{ route('register') }}" class="mt-2 inline-flex h-[42px] w-full items-center justify-center rounded-[10px] bg-brand px-5 text-sm font-semibold text-white hover:bg-brand-dark">Sign up free</a>
                    @endauth
                </div>
            </details>
        </div>
    </header>

    <section class="bg-page">
        <div class="mx-auto grid max-w-site items-center gap-12 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:py-20">
            <div>
                <p class="up spark inline-flex items-center gap-2 text-sm font-semibold text-brand"><span class="h-2 w-2 rounded-full bg-brand"></span>Never Miss an Expiry Date Again.</p>
                <h1 class="up d2 mt-3 text-4xl font-bold leading-tight text-ink sm:text-5xl">Every document. Every expiry. One place.</h1>
                <p class="up d3 mt-4 text-lg text-muted">Track insurance, licenses, and contracts in one place, and get reminded before they lapse.</p>
                <div class="up d4 mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-brand px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-dark">Start free</a>
                    <a href="#how" class="inline-flex items-center justify-center rounded-xl border border-line bg-surface px-4 py-2.5 text-sm font-semibold text-ink hover:bg-page">See how it works</a>
                </div>
                <ul class="up d4 mt-6 space-y-2 text-sm text-body">
                    <li class="flex gap-2"><span class="text-brand">✓</span> One list for every certificate and contract</li>
                    <li class="flex gap-2"><span class="text-brand">✓</span> Email at 30, 15, and 7 days</li>
                    <li class="flex gap-2"><span class="text-brand">✓</span> Private files, only for your company</li>
                </ul>
            </div>
            <div class="hframe rise">
                <div class="hwin">
                    <div class="dots" aria-hidden="true"><span></span><span></span><span></span></div>
                    <img class="win fl" src="{{ asset('images/landing/documents-hero.png') }}" alt="Documents">
                </div>
            </div>
        </div>
    </section>

    <section id="problem" class="px-4 py-16 sm:px-6 sm:py-20">
        <div class="mx-auto max-w-site">
            <div class="rv mx-auto max-w-3xl text-center">
                <h2 class="text-3xl font-bold text-ink sm:text-4xl">Managing document expiry dates shouldn't be complicated</h2>
                <p class="mt-3 text-muted">Keeping track of multiple certificates, licenses, insurance policies, and contracts can quickly become overwhelming.</p>
            </div>
            <div class="stack mt-12 space-y-6">
                @foreach ([
                    ['bg-panel-1', '01', 'Stay ahead', 'Never miss an expiry again', 'Missing expiry dates', 'A certificate or license lapses and nobody notices until someone asks for it.', 'calendar-x', 'Forgotten renewals', 'Insurance certificates and licenses expire because the reminder was never sent.', 'bell', 'documents.png', 'Documents'],
                    ['bg-panel-2', '02', 'One place', 'Replace scattered files with one list', 'Spreadsheets everywhere', 'Dates sit in separate Excel files, so the latest version is never obvious.', 'table', 'Manual contractor checks', 'Someone has to open each vendor file to see whether cover is still valid.', 'shield-check', 'contractors.png', 'Contractors'],
                    ['bg-panel-3', '03', 'Save time', 'Less chasing, more actual work', 'Hours of chasing', 'Tracking documents and emailing people takes time away from the actual work.', 'clock', 'Simple and automated', 'Doc2Due makes document expiry management simple, organized, and automated.', 'sparkle', 'add-document.png', 'Add document'],
                ] as [$bg, $num, $eyebrow, $title, $a, $at, $ai, $b, $bt, $bi, $image, $alt])
                    <article class="panel relative text-white shadow-card {{ $bg }}">

                        {{-- Left column: top-aligned, stagger-revealed children --}}
                        <div class="flex flex-col justify-start">
                            <p class="panel-child text-xs font-semibold uppercase tracking-widest text-white/70">{{ $eyebrow }}</p>
                            <h3 class="panel-child mt-3 max-w-sm text-2xl font-bold leading-snug">{{ $title }}</h3>
                            <div class="mt-6 grid grid-cols-2 gap-3">
                                {{-- Card 1 --}}
                                <div class="panel-child flex flex-col gap-2 rounded-[16px] bg-panel-soft p-[18px]">
                                    <x-icon :name="$ai" :stroke="1.7" class="h-[22px] w-[22px] shrink-0 text-brand-mid" />
                                    <span class="text-[16px] font-semibold leading-snug text-ink">{{ $a }}</span>
                                    <span class="text-[13px] leading-relaxed text-panel-muted">{{ $at }}</span>
                                </div>
                                {{-- Card 2 --}}
                                <div class="panel-child flex flex-col gap-2 rounded-[16px] bg-panel-soft p-[18px]">
                                    <x-icon :name="$bi" :stroke="1.7" class="h-[22px] w-[22px] shrink-0 text-brand-mid" />
                                    <span class="text-[16px] font-semibold leading-snug text-ink">{{ $b }}</span>
                                    <span class="text-[13px] leading-relaxed text-panel-muted">{{ $bt }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Right column: number above screenshot, never overlapping --}}
                        <div class="flex flex-col gap-3">
                            {{-- Number: visible always, counts up on scroll-in --}}
                            <p class="panel-num self-end text-[42px] font-semibold leading-none text-white"
                               data-target="{{ $loop->iteration }}">{{ $num }}</p>
                            {{-- Screenshot: translucent ring → browser frame --}}
                            <div class="pwin-ring win fl">
                                <div class="pwin-frame">
                                    <div class="pwin-bar" aria-hidden="true">
                                        <span></span><span></span><span></span>
                                    </div>
                                    <img class="pwin-img" src="{{ asset('images/landing/'.$image) }}" alt="{{ $alt }}">
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="close rv mt-8 rounded-xl border border-cream-deep bg-cream px-6 py-3.5 text-center">
                <p class="font-medium text-ink">Doc2Due makes document expiry management simple, organized, and automated.</p>
            </div>
        </div>
    </section>

   

    <section id="features" class="fc px-4 py-16 sm:px-6 sm:py-20">
        <div class="mx-auto max-w-site">
            <p class="rv mb-2 text-center text-xs font-semibold uppercase tracking-widest text-brand">Features</p>
            <h2 class="rv text-center text-3xl font-bold text-ink sm:text-4xl">Everything you need to stay current</h2>
            <div class="mt-12 grid gap-x-10 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['Expiry dashboard', 'See expired, due-soon, and safe documents together.'],
                    ['30/15/7-day reminders', 'Admins are emailed at 30, 15, and 7 days, then once after expiry.'],
                    ['Document upload', 'Store private PDF and JPEG files up to 2 MB.'],
                    ['Contractor management', 'Keep the contractor behind each certificate.'],
                    ['Multiple employees', 'Invite employees into one company.'],
                    ['CSV export', 'Download title, category, contractor, expiry date, and status.'],
                ] as $index => [$title, $text])
                    <div class="rv flex gap-4">
                        <span class="tile flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-brand text-white">
                            <x-icon :name="['chart', 'bell', 'upload', 'team', 'users', 'download'][$index]" :stroke="1.7" />
                        </span>
                        <div>
                            <h3 class="font-semibold text-ink">{{ $title }}</h3>
                            <p class="mt-1 text-sm text-muted">{{ $text }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
     <section id="how" class="px-4 py-16 sm:px-6 sm:py-20">
        <div class="mx-auto max-w-site">
            <p class="rv mb-2 text-center text-xs font-semibold uppercase tracking-widest text-brand">How it works</p>
            <h2 class="rv text-center text-3xl font-bold text-ink sm:text-4xl">Four steps, then the reminders run</h2>
            <ol class="relative mt-14 grid gap-10 lg:grid-cols-4">
                <span class="absolute left-[12%] right-[12%] top-6 hidden border-t border-dashed border-line lg:block" aria-hidden="true"></span>
                @foreach ([
                    ['Create your company', 'Register and you start as the admin on the free Starter plan.'],
                    ['Add documents and dates', 'Upload a PDF or photo and set the category, contractor, and expiry.'],
                    ['Invite your employees', 'Add teammates so they only see this company’s records.'],
                    ['Get reminders before expiry', 'Admins are emailed at 30, 15, and 7 days, then once when it lapses.'],
                ] as $index => [$title, $text])
                    <li class="rv relative text-center">
                        <span class="relative z-10 mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-brand text-white">
                            <x-icon :name="['home', 'upload', 'team', 'bell'][$index]" :stroke="1.7" class="h-6 w-6" />
                        </span>
                        <h3 class="mt-5 font-semibold text-ink">{{ $title }}</h3>
                        <p class="mt-2 text-sm text-muted">{{ $text }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
 <section id="who" class="px-4 pb-16 sm:px-6">
        <div class="who-box mx-auto max-w-site bg-brand-mid text-white">
            <div class="rv">
                <p class="text-xs font-semibold uppercase tracking-widest text-white/70">Who it's for</p>
                <h2 class="mt-3 text-3xl font-bold">Never miss a policy or contract expiry date</h2>
                <p class="mt-3 text-white/80">Doc2Due is built for teams that juggle documents across properties, sites, clients and suppliers.</p>
                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach (['Insurance policies', 'Contracts', 'Licenses', 'Certificates', 'Supplier documents'] as $pill)
                        <span class="rounded-full bg-white/15 px-3 py-1.5 text-sm">{{ $pill }}</span>
                    @endforeach
                </div>
            </div>
            <ol class="wr rv divide-y divide-white/15">
                @foreach ([
                    ['Property management companies', 'Keep insurance policies, safety certificates and contractor agreements for every property in one place.'],
                    ['Construction contractors', 'Track licenses, workers\' compensation and public liability cover so every site starts fully covered.'],
                    ['Cleaning companies', 'Stay on top of trade licenses, insurance policies and client contracts before they lapse.'],
                    ['Equipment rental companies', 'Watch equipment registrations, rental contracts and maintenance agreements so nothing runs out.'],
                    ['Small businesses managing supplier documents', 'Check supplier certificates, licenses and contracts without chasing vendors by email.'],
                ] as $index => [$title, $text])
                    <li class="flex gap-4 py-4 first:pt-0">
                        <span class="font-semibold text-white/70">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span><span class="block font-semibold">{{ $title }}</span><span class="mt-1 block text-sm text-white/80">{{ $text }}</span></span>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
   

    <section id="pricing" class="px-4 py-16 sm:px-6 sm:py-20">
        <div class="mx-auto max-w-site">
            <p class="rv mb-2 text-center text-xs font-semibold uppercase tracking-widest text-brand">Pricing</p>
            <h2 class="rv text-center text-3xl font-bold text-ink sm:text-4xl">Simple yearly plans</h2>
            <div class="mt-10 grid items-stretch gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach (config('plans') as $slug => $plan)
                    @php
                        $name = $plan['name'];
                        $limit = $plan['document_limit'].' documents';
                        $price = $plan['price_inr'] === 0 ? 'Free' : '₹'.number_format($plan['price_inr']);
                        $popular = $slug === 'basic';
                    @endphp
                    <article @class(['pc rv relative flex min-h-[22rem] flex-col bg-surface p-6 shadow-card', 'z-10 rounded-panel border-2 border-brand lg:-mt-3' => $popular, 'rounded-card border border-line' => ! $popular])>
                        @if ($popular)
                            <span class="absolute left-1/2 top-0 -translate-x-1/2 -translate-y-1/2 rounded-full bg-brand px-3 py-1 text-xs font-semibold text-white">Most Popular</span>
                        @endif
                        <p class="text-xs font-semibold uppercase tracking-wide text-muted">{{ $limit }}</p>
                        <h3 class="mt-2 text-xl font-bold text-ink">{{ $name }}</h3>
                        <p class="mt-6 text-4xl font-bold text-ink">{{ $price }}@if ($price !== 'Free')<span class="text-base font-medium text-muted">/year</span>@endif</p>
                        <ul class="mt-4 space-y-2 text-sm text-body">
                            <li class="flex items-center gap-2"><x-icon name="check" :stroke="1.7" class="h-4 w-4 text-brand" />Up to {{ $limit }}</li>
                            <li class="flex items-center gap-2"><x-icon name="check" :stroke="1.7" class="h-4 w-4 text-brand" />30, 15 and 7 day reminders</li>
                            <li class="flex items-center gap-2"><x-icon name="check" :stroke="1.7" class="h-4 w-4 text-brand" />Private company files</li>
                        </ul>
                        <a href="{{ route('register') }}" @class(['mt-auto inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold', 'bg-brand text-white hover:bg-brand-dark' => $popular, 'border border-line text-ink hover:bg-page' => ! $popular])>Get started</a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="faq" class="bg-page px-4 py-16 sm:px-6 sm:py-20" x-data="{ item: 0 }">
        <div class="mx-auto max-w-site">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-xs font-semibold uppercase tracking-widest text-brand-legacy">Frequently Asked Questions</p>
                <h2 class="mt-2 text-3xl font-bold text-ink sm:text-4xl">Questions? Look here.</h2>
                <p class="mt-3 text-muted">Find quick answers about keeping documents and important dates on track.</p>
            </div>
            @php
                $faqs = [
                    ['What does Doc2Due do?', 'It keeps insurance, licenses, permits, and contracts in one company list and emails admins before a date lapses.'],
                    ['Who is it for?', 'Property, construction, cleaning, equipment rental, and small teams that renew the same kinds of documents every year.'],
                    ['How do reminders work?', 'Company admins are emailed at 30, 15, and 7 days before expiry, and once after a document expires. The same reminder is never sent twice.'],
                    ['Who can see our files?', 'Only people in your company. Files stay on a private disk and download through an authorized link.'],
                    ['Can I add employees?', 'Yes. An admin can invite employees, and they only see your company’s records.'],
                    ['Can I change my plan later?', 'Yes. An admin can pay for a yearly plan from the billing page. Documents are not deleted when a plan ends.'],
                    ['What file types are supported?', 'PDF and JPEG files up to 2 MB.'],
                ];
            @endphp
            <div class="mt-10 grid grid-cols-1 gap-4 lg:grid-cols-2 sm:gap-5">
                @foreach ([0, 1] as $column)
                    <div class="flex flex-col gap-4 sm:gap-5">
                        @foreach ($faqs as $index => [$question, $answer])
                            @if ($index % 2 === $column)
                                <article class="rounded-card border border-line bg-surface p-5 shadow-card sm:p-6">
                                    <button
                                        type="button"
                                        class="flex w-full items-center justify-between gap-4 text-left font-semibold text-ink"
                                        aria-controls="faq-answer-{{ $index }}"
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                        x-bind:aria-expanded="item === {{ $index }}"
                                        @click="item = item === {{ $index }} ? null : {{ $index }}"
                                    >
                                        <span>{{ $question }}</span>
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center text-brand-legacy" aria-hidden="true">
                                            <svg
                                                class="h-5 w-5 transition-transform duration-300 ease-in-out motion-reduce:transition-none"
                                                :class="item === {{ $index }} ? 'rotate-45' : 'rotate-0'"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                            >
                                                <path d="M12 5v14M5 12h14" />
                                            </svg>
                                        </span>
                                    </button>
                                    <div id="faq-answer-{{ $index }}" class="faq-panel" :class="item === {{ $index }} ? 'open' : ''" aria-hidden="{{ $index === 0 ? 'false' : 'true' }}" x-bind:aria-hidden="item !== {{ $index }}">
                                        <div><p class="pt-4 text-sm leading-relaxed text-muted">{{ $answer }}</p></div>
                                    </div>
                                </article>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <x-footer />
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navLinks = document.querySelectorAll('header nav a[href^="#"]');
            const navSections = [...new Set([...navLinks].map((link) => document.querySelector(link.getAttribute('href'))).filter(Boolean))];
            const visibleSections = new Map();

            if ('IntersectionObserver' in window) {
                const navObserver = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            visibleSections.set(entry.target.id, entry.intersectionRatio);
                        } else {
                            visibleSections.delete(entry.target.id);
                        }
                    });

                    const activeSectionId = [...visibleSections.entries()].sort((a, b) => b[1] - a[1])[0]?.[0];
                    navLinks.forEach((link) => {
                        const isActive = link.getAttribute('href') === `#${activeSectionId}`;
                        link.classList.toggle('text-brand', isActive);
                        link.classList.toggle('text-muted', !isActive);
                    });
                }, { rootMargin: '-20% 0px -65% 0px', threshold: 0 });

                navSections.forEach((section) => navObserver.observe(section));
            }

            // ── Count-up helper ──────────────────────────────────────────
            function countUp(el, target, duration) {
                const start = performance.now();
                (function step(now) {
                    const p = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - p, 3); // ease-out cubic
                    el.textContent = String(Math.round(eased * target)).padStart(2, '0');
                    if (p < 1) requestAnimationFrame(step);
                })(performance.now());
            }

            // ── Stagger helper ───────────────────────────────────────────
            function revealPanel(panel) {
                if (panel.dataset.revealed) return;
                panel.dataset.revealed = '1';
                panel.classList.add('in');

                // Stagger each .panel-child inside this panel
                const children = panel.querySelectorAll('.panel-child');
                children.forEach((child, i) => {
                    child.style.transitionDelay = (0.08 + i * 0.14) + 's';
                    child.classList.add('in');
                });

                // Count-up number
                const numEl = panel.querySelector('.panel-num');
                if (numEl) {
                    const target = parseInt(numEl.dataset.target, 10) || 0;
                    setTimeout(() => {
                        numEl.style.opacity = '1';
                        countUp(numEl, target, 750);
                    }, 60);
                }
            }

            // ── Intersection Observer ────────────────────────────────────
            const panels = document.querySelectorAll('.stack .panel');
            const others = document.querySelectorAll('.rv');

            if (!('IntersectionObserver' in window)) {
                panels.forEach(revealPanel);
                others.forEach(el => el.classList.add('in'));
                return;
            }

            // Panel observer (stagger + count-up)
            const panelObs = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) revealPanel(entry.target);
                });
            }, { threshold: 0.15 });
            panels.forEach(el => panelObs.observe(el));

            // Generic .rv observer (unchanged)
            const rvObs = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) entry.target.classList.add('in');
                });
            }, { threshold: 0.18 });
            others.forEach(el => rvObs.observe(el));
        });
    </script>
</body>
</html>

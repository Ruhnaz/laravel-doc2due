@props(['name', 'stroke' => 2])
@php
$icons = [
 'check' => '<path d="M5 12l5 5L20 7"/>',
 'brand' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18M9 16l2 2 4-4"/>',
 'dashboard' => '<path d="M3 11l9-8 9 8M5 10v10h14V10"/>',
 'documents' => '<path d="M7 3h7l5 5v13H7zM14 3v5h5"/>',
 'contractors' => '<path d="M16 20v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2M9.5 10a3.5 3.5 0 100-7 3.5 3.5 0 000 7M21 20v-2a4 4 0 00-3-3.9M16 3.1a3.5 3.5 0 010 6.8"/>',
 'categories' => '<path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z"/>',
 'billing' => '<path d="M3 6h18v12H3zM3 10h18"/>',
 'employees' => '<path d="M12 12a4 4 0 100-8 4 4 0 000 8M4 21a8 8 0 0116 0"/>',
 'profile' => '<path d="M12 12a4 4 0 100-8 4 4 0 000 8M4 21a8 8 0 0116 0"/>',
 'settings' => '<path d="M12 8a4 4 0 100 8 4 4 0 000-8zM12 2v3M12 19v3M2 12h3M19 12h3M5 5l2 2M17 17l2 2M19 5l-2 2M7 17l-2 2"/>',
 'logout' => '<path d="M9 21H5V3h4M16 17l5-5-5-5M21 12H9"/>',
 'menu' => '<path d="M4 7h16M4 12h16M4 17h16"/>',
 'close' => '<path d="M6 18L18 6M6 6l12 12"/>',
 'chevron-down' => '<path d="M6 9l6 6 6-6"/>',
 'sidebar' => '<path d="M4 5h16v14H4zM8 5v14"/>',
 'topnav' => '<path d="M4 7h16M4 12h10M4 17h16"/>',
 'calendar-x' => '<rect x="3" y="4.5" width="18" height="16" rx="3"/><path d="M8 2.5v4M16 2.5v4M3 10h18M10 14l4 4M14 14l-4 4"/>',
 'bell' => '<path d="M6 9a6 6 0 1 1 12 0c0 6 2.5 7.5 2.5 7.5h-17S6 15 6 9zM10 20a2 2 0 0 0 4 0"/>',
 'table' => '<rect x="3" y="3.5" width="18" height="17" rx="3"/><path d="M3 9.5h18M3 15h18M9.5 3.5v17M15 3.5v17"/>',
 'shield-check' => '<path d="M12 3l8 3v6c0 4.5-3.4 8-8 9-4.6-1-8-4.5-8-9V6l8-3z"/><path d="M8.5 12l2.5 2.5 4.5-5"/>',
 'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/>',
 'sparkle' => '<path d="M12 3c.8 5 3 7.2 8 8-5 .8-7.2 3-8 8-.8-5-3-7.2-8-8 5-.8 7.2-3 8-8z"/>',
 'chart' => '<rect x="3.5" y="3.5" width="17" height="17" rx="3.5"/><path d="M7.5 14.5l3-3 2.5 2.5 3.5-4.5"/>',
 'upload' => '<path d="M12 15.5V5M8 8.5L12 4.5l4 4M4.5 15v3.5c0 1 .8 1.5 1.5 1.5h12c.7 0 1.5-.5 1.5-1.5V15"/>',
 'download' => '<path d="M12 4.5V15M8 11.5l4 4 4-4M5 20h14"/>',
 'team' => '<circle cx="9" cy="8" r="3.5"/><circle cx="17" cy="9" r="2.2"/><path d="M3.5 19.5c.5-3.5 2.8-5.5 5.5-5.5s5 2 5.5 5.5M15.5 14.5c2.3-.3 4 1 4.5 3.5"/>',
 'users' => '<circle cx="12" cy="7.5" r="3"/><circle cx="5.5" cy="10" r="2.2"/><circle cx="18.5" cy="10" r="2.2"/><path d="M6.5 19.5c.4-3 2.5-5 5.5-5s5.1 2 5.5 5M1.5 18c.2-2 1.5-3.5 3.5-3.8M22.5 18c-.2-2-1.5-3.5-3.5-3.8"/>',
 'home' => '<path d="M4 11l8-7 8 7v8.5c0 .6-.4 1-1 1H5c-.6 0-1-.4-1-1V11z"/><path d="M10 20.5v-6h4v6"/>',
];
@endphp
<svg {{ $attributes->merge(['class' => 'h-5 w-5 shrink-0']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ $stroke }}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $icons[$name] ?? '' !!}</svg>


import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './app/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'system-ui', ...defaultTheme.fontFamily.sans],
                serif: ['Fraunces', 'Georgia', 'serif'],
                landing: ['Plus Jakarta Sans', 'Poppins', ...defaultTheme.fontFamily.sans],
                admin: ['Inter', 'system-ui', ...defaultTheme.fontFamily.sans],
            },
            maxWidth: { site: '1400px' },
            colors: {
                // Core palette (indigo and warm cream), used by the whole site
                brand: {
                    DEFAULT: '#363bb8', // primary buttons, links, active nav
                    legacy: '#2F39A9',
                    deep: '#2a2e96',
                    alt: '#2f34a8',
                    mist: '#f1f2fc',
                    cream: '#f8f3ea',
                    gold: '#d9c28f',
                    ink: '#1b1e4b',
                    lavender: '#c9ccf6',
                    wordmark: '#4a4fbf',
                    dark: '#222B82',    // hover
                    mid: '#343CB9',     // landing page panels and hero
                    light: '#5560D4',   // light end of gradients
                    soft: '#E8E9FA',    // tinted backgrounds, icon tiles
                    tint: '#F5F6FD',    // table hover, drop zones
                },
                panel: {
                    1: '#343CB9',       // landing page stacked cards
                    2: '#4650C8',
                    3: '#5560D4',
                    soft: '#E8EAFC',
                    muted: '#4A4E66',
                },
                cream: {
                    DEFAULT: '#FDEEE4', // callout boxes, hero glow
                    deep: '#F6DCCB',    // cream borders
                },
                page: '#F9F7F3',        // page background
                surface: '#FFFFFF',     // cards
                line: '#EAE7E1',        // borders and dividers
                ink: '#1B1F4B',         // headings
                body: '#292D35',        // body text
                muted: '#62626E',       // secondary text (passes contrast on white)
                accent: '#F28C38',      // stars and small highlights
                badge: '#EF5B4B',       // "Most chosen" style tags

                // Document status colors. Use: bg-status-expired-soft text-status-expired-text
                status: {
                    expired: { DEFAULT: '#C94B4B', text: '#A93A3A', soft: '#FBE9E9' },
                    soon:    { DEFAULT: '#C17A25', text: '#8F5A14', soft: '#FBF0E0' }, // 0-7 days
                    month:   { DEFAULT: '#9A7410', text: '#7A5B0A', soft: '#FDF6DD' }, // 8-30 days
                    valid:   { DEFAULT: '#43815D', text: '#2F6B47', soft: '#E6F2EA' }, // 31+ days
                },

                // Danger actions (delete, remove)
                danger: { DEFAULT: '#C94B4B', dark: '#A93A3A', soft: '#FBE9E9', line: '#E9C3C3' },

                // Backwards compatible: classes Cursor already used in the app
                app: {
                    primary: '#2F39A9',
                    'primary-dark': '#222B82',
                    'primary-light': '#E8E9FA',
                    bg: '#F9F7F3',
                    surface: '#FFFFFF',
                    text: '#292D35',
                    'text-2': '#62626E',
                    border: '#EAE7E1',
                    expired: '#C94B4B',
                    warning: '#C17A25',
                    success: '#43815D',
                },
            },

            backgroundImage: {
                'brand-gradient': 'linear-gradient(90deg, #5560D4 0%, #2F39A9 100%)',
            },

            borderRadius: {
                card: '16px',
                panel: '28px',
                hero: '36px',
            },

            boxShadow: {
                card: '0 1px 2px rgba(27, 31, 75, 0.04), 0 8px 24px rgba(34, 43, 130, 0.06)',
                lift: '0 16px 40px rgba(34, 43, 130, 0.12)',
                menu: '0 10px 30px rgba(34, 43, 130, 0.14)',
                dialog: '0 20px 60px rgba(27, 31, 75, 0.25)',
            },
        },
    },

    plugins: [forms],
};


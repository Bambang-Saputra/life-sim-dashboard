import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
        './app/Models/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                'sans':  ['Inter', 'system-ui', 'sans-serif'],
                'pixel': ['"Press Start 2P"', 'monospace'],
                'vt323': ['"VT323"', 'monospace'],
                'mono':  ['"Share Tech Mono"', 'ui-monospace', 'monospace'],
            },

            colors: {
                // ── EARTH PALETTE v3.0 (Stardew Valley Cozy) ──
                'soil':   { DEFAULT: '#83644A', light: '#A88B6E', dark: '#5C4632' },
                'grass':  { DEFAULT: '#6BA368', light: '#8FBC8A', dark: '#4E7D4C' },
                'corn':   { DEFAULT: '#E5B567', light: '#F1CC8E', dark: '#C99845' },
                'berry':  { DEFAULT: '#BE546E', light: '#D4869A', dark: '#9A3F56' },
                'sky':    { DEFAULT: '#77AADD', light: '#A8CDED', dark: '#5588BB' },
                'stone':  { DEFAULT: '#A9A39E', light: '#C9C4C0', dark: '#7B7672' },
                'cream':  { DEFAULT: '#F5EFE0', light: '#FBF7EC', dark: '#E8DEC4' },

                // ── Legacy compat (re-mapped to new palette) ──
                'sdv': {
                    'wheat':        '#F4E285',
                    'wheat-light':  '#FBF7EC',
                    'wheat-mid':    '#EDD87A',
                    'wheat-dark':   '#C9B850',
                    'grass':        '#6BA368',
                    'grass-light':  '#8FBC8A',
                    'grass-dark':   '#4E7D4C',
                    'pine':         '#2D6A4F',
                    'pine-light':   '#3A8A65',
                    'oak':          '#D4A373',
                    'oak-light':    '#E8C49A',
                    'oak-dark':     '#B8804A',
                    'soil':         '#83644A',
                    'soil-light':   '#A88B6E',
                    'soil-dark':    '#5C4632',
                    'river':        '#77AADD',
                    'river-light':  '#A8CDED',
                    'river-dark':   '#5588BB',
                    'barn':         '#BE546E',
                    'barn-light':   '#D4869A',
                    'barn-dark':    '#9A3F56',
                },
            },

            boxShadow: {
                'pixel-sm':   '2px 2px 0 rgba(92,70,50,0.25)',
                'pixel':      '3px 3px 0 rgba(92,70,50,0.30)',
                'pixel-lg':   '5px 5px 0 rgba(92,70,50,0.35)',
                'pixel-grass':'3px 3px 0 #4E7D4C',
                'pixel-berry':'3px 3px 0 #9A3F56',
                'cozy':       '0 2px 8px rgba(92,70,50,0.10), 0 1px 2px rgba(92,70,50,0.06)',
                'cozy-lg':    '0 4px 14px rgba(92,70,50,0.12), 0 2px 4px rgba(92,70,50,0.06)',
            },

            borderWidth: { '3': '3px' },

            animation: {
                'blink':     'blink 1s step-end infinite',
                'float':     'float 3s ease-in-out infinite',
                'shake':     'shake 0.3s ease-in-out',
                'level-up':  'levelUp 0.5s ease-out forwards',
                'twinkle':   'twinkle 2s ease-in-out infinite',
                'sun-shine': 'sunShine 4s ease-in-out infinite',
                'fade-in':   'fadeIn 0.3s ease-out',
            },

            keyframes: {
                blink:    { '0%,100%': { opacity: '1' }, '50%': { opacity: '0' } },
                float:    { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-6px)' } },
                shake:    { '0%,100%': { transform: 'translateX(0)' }, '25%': { transform: 'translateX(-3px)' }, '75%': { transform: 'translateX(3px)' } },
                levelUp:  { '0%': { transform: 'scale(1)' }, '50%': { transform: 'scale(1.05)' }, '100%': { transform: 'scale(1)' } },
                twinkle:  { '0%,100%': { opacity: '0.4' }, '50%': { opacity: '1' } },
                sunShine: { '0%,100%': { filter: 'brightness(1)' }, '50%': { filter: 'brightness(1.15)' } },
                fadeIn:   { '0%': { opacity: '0', transform: 'translateY(4px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
            },
        },
    },

    plugins: [
        forms,
        function({ addUtilities }) {
            addUtilities({
                '.pixelated':         { 'image-rendering': 'pixelated' },
                '.pixel-box':         { 'border-radius': '0', 'image-rendering': 'pixelated' },
                '.text-pixel-shadow': { 'text-shadow': '1px 1px 0 rgba(92,70,50,0.35)' },
            });
        },
    ],
};

<div align="center">

# Life-Sim Dashboard

**A pixel-art personal life management app: quests, finances, and a media library in one place.**

![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-4.x-FB70A9?logo=livewire&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?logo=alpinedotjs&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.x-06B6D4?logo=tailwindcss&logoColor=white)
![Tests](https://img.shields.io/badge/tests-25%20passing-4E7D4C)
![License](https://img.shields.io/badge/license-MIT-83644A)

</div>

---

## Overview

Life-Sim Dashboard turns everyday life management into a game you actually want to open. Every module speaks the same visual language, a hand-built pixel-art design system (banded skies, dithered gradients, stair-stepped silhouettes) synced 1:1 with its Figma source, and every meaningful action gives feedback: XP, streaks, chiptune sound effects, and achievement unlocks.

| Module | What it does |
|---|---|
| **Quest Board** | Daily tasks as RPG quests: difficulty tiers (Easy to Legendary), XP rewards, progress bars, deadlines with browser-notification alarms, impact flags, and per-quest history notes |
| **Daily Habits** | Recurring quests that respawn every morning and feed the completion streak |
| **Gold Ledger** | Income and expense tracking with search, date-range filters, monthly navigation, and CSV/PDF export |
| **Budget Board** | Per-category monthly limits rendered as game-style HP bars: green, amber, then blinking red on overspend |
| **Finance Insights** | Automatic month-over-month analysis: fair MTD comparisons, category spikes, daily-average projections, expense-to-income ratio |
| **Savings Tracker** | Multiple saving goals with targets, deadlines, editable deposit history, and 6-month charts |
| **Library Wing** | Movie, TV, anime, and manga collection via TMDB and Jikan: trending shelf, paginated search, personal ratings, automatic S/A/B/C/D tier list, and taste statistics |
| **Achievements** | 12 cross-module unlocks with a trophy rack and a global celebration banner |

## The Game Layer

- **XP and levels**: every completed quest awards XP (10-100 by difficulty); the level is derived server-side from the database, so it can never go stale
- **Streaks**: consecutive-day completion tracking with an all-time record, computed from real quest history
- **Achievements**: 12 definitions evaluated after meaningful actions (quests, ratings, transactions, deposits); unlocks are idempotent and celebrated globally
- **Chiptune SFX**: coin, complete, fanfare, and unlock jingles synthesized live with the Web Audio API (square-wave oscillators, zero audio files, mutable from the navbar)
- **Living pixel scenes**: each page hero is an interactive scene with mouse parallax on three depth layers, a trophy that bursts into sparkles, a vault that shakes and pops a coin, a TV that turns on, and books that lift on hover

## Design System

The UI implements a Figma-first pixel design language (file: *Life-Sim, Cozy Pixel Skin*):

- **Earth palette**: seven color families (soil, grass, corn, berry, sky, stone, cream) in three shades each, exposed as CSS custom properties
- **Authentic pixel techniques**: banded color ramps instead of gradients, checkerboard dithering via `repeating-conic-gradient`, stair-stepped silhouettes via `clip-path`
- **Typography contract**: Press Start 2P strictly for headings, labels, and numbers; Inter for body text and data
- **Motion with care**: skeleton shimmer loaders, staggered card entrances, tab transitions, HP bars animating in `steps(12)`, all gated behind `prefers-reduced-motion`

## Security

- **Single-owner lock**: registration closes automatically after the first account (all data is personal; a second account would see everything)
- Login rate-limited twice: per email and IP (5 attempts, Breeze) and per IP (`throttle:10,1`)
- Session regeneration on login, full invalidation on logout, bcrypt-12 password hashing
- Security-header middleware: `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`
- Hot-path database indexes on all date and type columns

## Getting Started

**Requirements:** PHP 8.2 or newer, Composer, Node.js 18 or newer, MySQL/MariaDB (on Windows, [Laragon](https://laragon.org/) is the easy path).

```bash
git clone https://github.com/Bambang-Saputra/life-sim-dashboard.git
cd life-sim-dashboard

composer install && npm install
cp .env.example .env
php artisan key:generate

# set DB credentials in .env, then:
php artisan migrate
php artisan db:seed        # optional: full demo dataset
npm run build
php artisan serve          # -> http://127.0.0.1:8000
```

Register once; that account becomes the owner and registration locks itself.

### Demo mode

`php artisan db:seed` fills every module with realistic, date-relative data: a 6-day quest streak at level 4, three months of finances with budgets in all three HP-bar states, savings history, and a rated S-to-D tier list. It also creates the owner account `demo@example.com` / `demo1234`. Set `APP_DEMO=true` in `.env` to display those credentials on the login page for portfolio deployments.

### API keys

| Provider | Used for | Key required |
|---|---|---|
| [TMDB](https://www.themoviedb.org/settings/api) | Movie and TV search/trending | Free key, set `TMDB_API_KEY` in `.env` |
| [Jikan](https://jikan.moe) (MyAnimeList) | Anime and manga | None |

### Mail (password reset)

Out of the box `MAIL_MAILER=log`, so password-reset links are written to `storage/logs/laravel.log` instead of being sent. To deliver real email through Gmail:

1. Create an App Password at [myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords) (requires 2-Step Verification).
2. In `.env`, set:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your-16-char-app-password
MAIL_FROM_ADDRESS=your@gmail.com
```

3. Run `php artisan config:clear`.

Any SMTP provider (Mailgun, Resend, Brevo, and others) works the same way; only the host and credentials change.

### Scheduler (optional but recommended)

Daily habit spawning (00:05) and recurring-transaction posting (00:10) run via Laravel's scheduler:

```bash
php artisan schedule:work
```

Both jobs are idempotent and also fire as a safety net when the relevant page is opened, so nothing breaks if the scheduler never runs.

## Testing

```bash
php artisan test          # feature tests (sqlite in-memory)
```

The suite covers login, the registration lock, password reset, e-mail verification, session handling, and profile management: 25 tests, 61 assertions.

## Architecture

```
app/
├── Livewire/          # 12 components; pages are thin, components own their state
├── Models/            # Eloquent + domain logic (RecurringQuest::spawnDue, Budget::spentThisMonth)
├── Support/           # PlayerProgress (XP/streak math), Achievements (definitions + evaluation)
├── Services/          # TmdbService, JikanService; HTTP, caching, normalization
└── Http/Middleware/   # SecurityHeaders

resources/
├── css/app.css        # design tokens, component classes, pixel-scene engine, ~40 keyframes
├── js/app.js          # Livewire manual bundle + Alpine components (charts, pixelScene, alarms)
└── views/livewire/    # one blade per component
```

Key decisions:

- **Livewire manual bundle** (`livewire.esm` + `Livewire.start()`) so Alpine components register before the DOM walk, avoiding double-Alpine bugs
- **Derived state over stored state**: XP, levels, streaks, and tier lists are computed from source tables, so they can never drift
- **Idempotency keys** for anything time-based (`last_posted_period`, `last_spawned_date`)
- **Pure CSS/DOM pixel art**: no sprite images, no canvas; scenes are DOM nodes using the same palette tokens as the UI

## Roadmap

- [ ] Multi-wallet support (cash, bank, e-wallet) with transfers
- [ ] Bank CSV import and reconciliation
- [ ] Quest calendar view
- [ ] Dark-mode toggle (night tokens already in place)
- [ ] Live demo deployment and screenshot gallery

## License

MIT.

<div align="center">
<sub>Built by <a href="https://github.com/Bambang-Saputra">Bambang Saputra</a></sub>
</div>

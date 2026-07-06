<div align="center">

# 🌾 Life-Sim Dashboard

**A cozy, pixel-art personal life management app — run your life like a farm sim.**

Quests with XP & streaks · Personal finance with budgets & insights · Media library with tier lists

![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-4.x-FB70A9?logo=livewire&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?logo=alpinedotjs&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.x-06B6D4?logo=tailwindcss&logoColor=white)
![Tests](https://img.shields.io/badge/tests-18%20passing-4E7D4C)
![License](https://img.shields.io/badge/license-MIT-83644A)

</div>

---

## ✨ Overview

Life-Sim Dashboard turns everyday life management into a game you actually want to open. Every module speaks the same visual language — a hand-built **pixel-art design system** (banded skies, dithered gradients, stair-stepped hills) synced 1:1 with its Figma source — and every meaningful action gives feedback: XP, streaks, chiptune sound effects, confetti, and achievement unlocks.

| Module | What it does |
|---|---|
| ⚔ **Quest Board** | Daily tasks as RPG quests — difficulty tiers (Easy → Legendary), XP rewards, progress bars, deadlines with browser-notification alarms, impact flags, and per-quest history notes |
| 🔄 **Daily Habits** | Recurring quests that respawn every morning and feed your streak 🔥 |
| 💰 **Gold Ledger** | Income/expense tracking with search & date-range filters, monthly navigation, and CSV/PDF export |
| 🛡️ **Budget Board** | Per-category monthly limits rendered as game-style **HP bars** — green, amber, then blinking red when you overspend |
| 🧠 **Finance Insights** | Automatic month-over-month analysis: fair MTD comparisons, biggest category spikes, daily-average projections, expense/income ratio |
| 🐷 **Savings Tracker** | Multiple saving goals with targets, deadlines, deposit history (editable), and 6-month charts |
| 📚 **Library Wing** | Movie/TV/anime/manga collection via TMDB & Jikan — trending shelf, paginated search, personal ratings, auto **S/A/B/C/D tier list**, and taste statistics |
| 🏆 **Achievements** | 12 cross-module unlocks with a trophy rack, celebration banner, and confetti |

## 🎮 The Game Layer

- **XP & Levels** — every completed quest awards XP (10–100 by difficulty); level = derived server-side from the database, never stale
- **Streaks** — consecutive-day completion tracking with an all-time record, computed from real quest history
- **Achievements** — 12 definitions evaluated after meaningful actions (quests, ratings, transactions, deposits); unlocks are idempotent and celebrated globally
- **Chiptune SFX** — coin, level-complete, fanfare, and unlock jingles synthesized live with the **Web Audio API** (square-wave oscillators — zero copyrighted audio files, mutable from the navbar)
- **Living pixel scenes** — each page hero is an interactive scene: mouse parallax on 3 depth layers, a trophy that bursts into sparkles, a vault that shakes and pops a coin, a TV that turns on, books that lift on hover

## 🎨 Design System

The UI implements a Figma-first pixel design language (file: *Life-Sim — Cozy Pixel Skin*):

- **Earth palette** — 7 color families (soil/grass/corn/berry/sky/stone/cream) × 3 shades, exposed as CSS custom properties
- **Authentic pixel techniques** — banded color ramps instead of gradients, checkerboard dithering via `repeating-conic-gradient`, stair-stepped silhouettes via `clip-path`
- **Typography contract** — *Press Start 2P* strictly for headings, labels, and numbers; *Inter* for body and data
- **Motion with care** — skeleton shimmer loaders, staggered card entrances, tab transitions, HP bars animating in `steps(12)` — all gated behind `prefers-reduced-motion`

## 🔐 Security

- **Single-owner lock** — registration closes automatically after the first account (all data is personal; a second account would see everything)
- Login rate-limited twice: per email+IP (5 attempts, Breeze) and per IP (`throttle:10,1`)
- Session regeneration on login, full invalidation on logout, bcrypt-12 hashed passwords
- Security headers middleware: `X-Frame-Options`, `nosniff`, `Referrer-Policy`, `Permissions-Policy`
- Hot-path database indexes on all date/type columns

## 🚀 Getting Started

**Requirements:** PHP ≥ 8.2, Composer, Node.js ≥ 18, MySQL/MariaDB (on Windows, [Laragon](https://laragon.org/) is the easy path).

```bash
git clone https://github.com/Bambang-Saputra/life-sim-dashboard.git
cd life-sim-dashboard

composer install && npm install
cp .env.example .env
php artisan key:generate

# set DB credentials in .env, then:
php artisan migrate
php artisan db:seed        # optional demo data
npm run build
php artisan serve          # → http://127.0.0.1:8000
```

Register once — that account becomes the owner and registration locks itself.

### API keys

| Provider | Used for | Key required? |
|---|---|---|
| [TMDB](https://www.themoviedb.org/settings/api) | Movies & TV search/trending | Free key → `TMDB_API_KEY` in `.env` |
| [Jikan](https://jikan.moe) (MyAnimeList) | Anime & manga | None |

### Scheduler (optional but recommended)

Daily habit spawning (00:05) and recurring-transaction posting (00:10) run via Laravel's scheduler:

```bash
php artisan schedule:work
```

Both jobs are **idempotent** and also fire as a safety net when you open the relevant page — so nothing breaks if the scheduler never runs.

## 🧪 Testing

```bash
php artisan test          # feature tests (sqlite in-memory)
```

Auth suite: 18 tests / 38 assertions covering login, registration lock, password reset, e-mail verification, and session handling.

## 🏗️ Architecture Notes

```
app/
├── Livewire/          # 12 components — pages are thin, components own their state
├── Models/            # Eloquent + domain logic (RecurringQuest::spawnDue, Budget::spentThisMonth)
├── Support/           # PlayerProgress (XP/streak math), Achievements (definitions + evaluation)
├── Services/          # TmdbService, JikanService — HTTP + caching + normalization
└── Http/Middleware/   # SecurityHeaders

resources/
├── css/app.css        # design tokens, component classes, pixel-scene engine, ~40 keyframes
├── js/app.js          # Livewire manual bundle + Alpine components (charts, pixelScene, alarms)
└── views/livewire/    # one blade per component
```

Key decisions:

- **Livewire manual bundle** (`livewire.esm` + `Livewire.start()`) so Alpine components register before DOM walk — no double-Alpine bugs
- **Derived state over stored state** — XP, levels, streaks, and tier lists are computed from source tables, so they can never drift
- **Idempotency keys** for anything time-based (`last_posted_period`, `last_spawned_date`)
- **Pure CSS/DOM pixel art** — no sprite images, no canvas; scenes are DOM nodes with the same palette tokens as the UI

## 🗺️ Roadmap

- [ ] Multi-wallet support (cash / bank / e-wallet) with transfers
- [ ] Bank CSV import & reconciliation
- [ ] Quest calendar view
- [ ] Dark-mode toggle (theme-night tokens already in place)
- [ ] Live demo deployment + screenshot gallery

## 📄 License

MIT — fork it, reskin it, make it your own farm.

<div align="center">
<sub>Built with ❤️ (and a lot of 8px grids) by <a href="https://github.com/Bambang-Saputra">Bambang Saputra</a></sub>
</div>

# 🌾 Life-Sim Dashboard

> Personal life management dashboard dengan tema cozy Stardew Valley.
> Stack: **Laravel 13** · **Livewire 4** · **Alpine.js** · **Tailwind CSS** · **Chart.js**

![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4)
![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20)
![Livewire](https://img.shields.io/badge/Livewire-4.x-FB70A9)
![Tailwind](https://img.shields.io/badge/Tailwind-3.x-06B6D4)

---

## ✨ Fitur

| Modul | Deskripsi |
|---|---|
| 🏡 **Dashboard** | Overview ringkas — banner pixel art time-aware (pagi/siang/sore/malam), top quests, finance summary, recent library items |
| ⚔ **Quest Board** | Task management RPG-style: priority (easy/normal/hard/legendary), XP reward, progress bar, alarm, important flag, history notes |
| 💰 **Gold Ledger** | Catatan keuangan personal — income/expense dengan kategori, chart bulanan, top spending categories, trend 6 bulan |
| 🏖 **Tabungan** | Tabungan goal-based (multi-akun) dengan target amount, target date, progress %, riwayat setoran 6 bulan |
| 📚 **Library Wing** | Tracking film/TV/anime/manga via TMDB + Jikan API. Default tampilkan trending. Rating personal, status (Plan/Ongoing/Done/Drop) |

---

## 🚀 Setup Local

### Prerequisites

- **PHP ≥ 8.2** dengan extension: `pdo_mysql`, `mbstring`, `xml`, `bcmath`, `gd`, `curl`, `openssl`
- **Composer 2.x**
- **Node.js ≥ 18** + npm
- **MySQL/MariaDB** (rekomendasi: pakai [Laragon](https://laragon.org/) di Windows)

### Install

```bash
# 1. Clone repo
git clone https://github.com/Bambang-Saputra/life-sim-dashboard.git
cd life-sim-dashboard

# 2. Install dependencies
composer install
npm install

# 3. Copy env & generate key
cp .env.example .env
php artisan key:generate

# 4. Edit .env — set DB credentials & TMDB API key
# DB_DATABASE=life_sim_db
# DB_USERNAME=root
# DB_PASSWORD=
# TMDB_API_KEY=your_key_dari_themoviedb.org

# 5. Buat database & jalankan migrations
php artisan migrate

# 6. Build assets
npm run build

# 7. Jalankan server
php artisan serve
# → http://127.0.0.1:8000
```

---

## 🔑 API Keys

| API | Untuk | Gratis? | Cara dapat |
|---|---|---|---|
| **TMDB** | Movies + TV Series | ✅ Gratis | [themoviedb.org/settings/api](https://www.themoviedb.org/settings/api) → Request API Key → Developer tier |
| **Jikan** | Anime + Manga | ✅ Gratis, **tanpa key** | Otomatis bekerja |

Setelah dapat TMDB key, edit `.env`:
```env
TMDB_API_KEY=your_key_here
```

---

## 🛠 Development

```bash
# Terminal 1 — Laravel server
php artisan serve

# Terminal 2 — Vite hot reload (saat edit CSS/JS)
npm run dev

# Setelah edit selesai → build production assets
npm run build
```

⚠️ **Catatan Windows:** Setelah `npm run dev`, hapus `public/hot` supaya tidak ada IPv6 lag:
```bash
rm public/hot
```

---

## 📅 Scheduled Tasks (opsional)

Untuk reminder otomatis (07:00 morning, 19:00 evening summary):
```bash
php artisan schedule:work
```

Atau di production, daftarkan ke cron:
```cron
* * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🗄 Database Schema

```
quests              → id, title, description, history, priority,
                     category, due_at, alarm_at, is_completed,
                     is_important, progress (0-100), xp_reward,
                     completed_at, timestamps

finance_entries     → id, type (in/out), amount, category,
                     description, recorded_at, timestamps

savings             → id, name, target_amount, target_date,
                     icon, color, note, is_active, timestamps

saving_deposits     → id, saving_id (FK), amount,
                     deposited_at, note, timestamps

library_items       → id, api_type (movie/tv/anime/manga),
                     external_id, title, cover_image, genre,
                     personal_rating, personal_review,
                     status (plan_to/ongoing/completed/dropped),
                     metadata (JSON), timestamps
```

---

## 📁 Struktur Project

```
app/
├── Livewire/                  ← Komponen interaktif Livewire
│   ├── QuestBoard.php
│   ├── QuestSummary.php       (widget dashboard)
│   ├── GoldLedger.php
│   ├── FinanceSummary.php     (widget dashboard)
│   ├── FinanceCharts.php      (Chart.js)
│   ├── SavingsTracker.php
│   ├── LibraryWing.php
│   └── LibrarySummary.php     (widget dashboard)
├── Models/
│   ├── Quest.php
│   ├── FinanceEntry.php
│   ├── Saving.php
│   ├── SavingDeposit.php
│   └── LibraryItem.php
└── Services/
    ├── TmdbService.php        ← Wrapper TMDB API
    └── JikanService.php       ← Wrapper Jikan API

resources/views/
├── layouts/
│   ├── app.blade.php          ← Layout dengan nav atas + live clock
│   └── guest.blade.php        ← Layout halaman auth
├── livewire/                  ← Templates Livewire components
├── dashboard.blade.php        (page /dashboard)
├── quests.blade.php           (page /quests)
├── finance.blade.php          (page /finance)
├── library.blade.php          (page /library)
└── welcome.blade.php          ← Landing page
```

---

## 🎨 Tema Design

Earth palette inspired by Stardew Valley:

| Token | Hex | Penggunaan |
|---|---|---|
| `soil` | `#83644A` | Primary text & borders |
| `grass` | `#6BA368` | Success, completed |
| `corn` | `#E5B567` | Accent, warnings |
| `berry` | `#BE546E` | Danger, errors |
| `sky` | `#77AADD` | Info, dates |
| `stone` | `#A9A39E` | Muted |
| `cream` | `#F5EFE0` | Backgrounds |

Typography: **Inter** (body) + **Press Start 2P** (titles).

---

## 📝 Roadmap

- [ ] Sprite-based karakter di banner (real PNG sprite sheet)
- [ ] Export Finance ke CSV/PDF
- [ ] Pagination saat data ratusan
- [ ] Filter Quest by category
- [ ] PWA support (offline-first)
- [ ] Persistensi XP ke DB (sekarang hanya localStorage)
- [ ] Multi-user support (sekarang single-owner personal app)

---

## 📄 License

MIT — silakan fork & modifikasi untuk kebutuhan pribadi.

Built with ❤️ by [Bambang Saputra](https://github.com/Bambang-Saputra)

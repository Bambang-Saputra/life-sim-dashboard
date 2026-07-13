<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\FinanceEntry;
use App\Models\LibraryItem;
use App\Models\Quest;
use App\Models\RecurringQuest;
use App\Models\RecurringTemplate;
use App\Models\Saving;
use App\Models\SavingDeposit;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Data demo lengkap untuk portfolio/deploy.
 *
 * Semua tanggal RELATIF ke now(), jadi kapan pun di-seed:
 * - streak selalu menyala (6 hari beruntun sampai hari ini)
 * - XP total 350 = Level 4 (50/100 menuju level 5)
 * - budget menampilkan 3 kondisi HP bar (aman, warning, jebol)
 * - insight bulanan punya pembanding bulan lalu
 * - chart tabungan terisi 6 bulan ke belakang
 * - tier list S sampai D terisi
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedUser();
        $this->seedQuests();
        $this->seedHabits();
        $this->seedFinanceHistory();
        $this->seedFinanceCurrentMonth();
        $this->seedBudgets();
        $this->seedRecurringTemplates();
        $this->seedSavings();
        $this->seedLibrary();
    }

    private function seedUser(): void
    {
        User::create([
            'name' => 'Demo Player',
            'email' => 'demo@example.com',
            'password' => 'demo1234',
            'email_verified_at' => now(),
        ]);
    }

    private function seedQuests(): void
    {
        $xp = ['easy' => 10, 'normal' => 20, 'hard' => 50, 'legendary' => 100];

        // [hari lalu, judul, prioritas, kategori, important, history]
        $done = [
            [13, 'Setup meja kerja baru',            'normal', 'personal', false, null],
            [12, 'Bayar tagihan listrik',            'easy',   'finance',  false, null],
            [10, 'Lari pagi 5K',                     'hard',   'health',   true,  null],
            [9,  'Baca 50 halaman Atomic Habits',    'normal', 'learning', false, null],
            [8,  'Meal prep seminggu',               'normal', 'health',   false, null],
            [7,  'Deep clean kamar kos',             'hard',   'personal', false, null],
            [5,  'Selesaikan laporan bulanan',       'hard',   'work',     true,  'Revisi 2x dari mentor, final approved.'],
            [4,  'Review budget mingguan',           'easy',   'finance',  false, null],
            [4,  'Olahraga pagi',                    'normal', 'health',   false, null],
            [3,  'Nulis jurnal refleksi',            'easy',   'personal', false, null],
            [2,  'Belajar Livewire 4',               'normal', 'learning', false, 'Manual bundle + entangle sudah paham.'],
            [2,  'Backup data laptop',               'easy',   'personal', false, null],
            [1,  'Meeting persiapan demo',           'normal', 'work',     false, null],
            [1,  'Olahraga pagi',                    'normal', 'health',   false, null],
            [0,  'Olahraga pagi',                    'normal', 'health',   false, null],
        ];

        $times = [[7, 40], [12, 10], [16, 25], [19, 45], [21, 5]];
        foreach ($done as $i => [$daysAgo, $title, $prio, $cat, $vip, $history]) {
            [$h, $m] = $times[$i % count($times)];
            Quest::create([
                'title'        => $title,
                'priority'     => $prio,
                'category'     => $cat,
                'is_important' => $vip,
                'history'      => $history,
                'is_completed' => true,
                'progress'     => 100,
                'xp_reward'    => $xp[$prio],
                'completed_at' => now()->subDays($daysAgo)->setTime($h, $m),
            ]);
        }

        // quest aktif: variasi progress, deadline, alarm, importance
        Quest::create([
            'title' => 'Deploy Life-Sim ke VPS', 'priority' => 'legendary', 'category' => 'work',
            'is_important' => true, 'progress' => 65, 'xp_reward' => $xp['legendary'],
            'due_at' => now()->addDays(3)->setTime(18, 0),
            'history' => 'Domain sudah dibeli. Tinggal setup nginx, SSL, dan scheduler.',
        ]);
        Quest::create([
            'title' => 'Belajar Chart.js advanced', 'priority' => 'normal', 'category' => 'learning',
            'progress' => 30, 'xp_reward' => $xp['normal'], 'due_at' => now()->addDays(5)->setTime(20, 0),
        ]);
        Quest::create([
            'title' => 'Servis motor + ganti oli', 'priority' => 'easy', 'category' => 'personal',
            'xp_reward' => $xp['easy'],
            'due_at' => now()->addDay()->setTime(9, 0), 'alarm_at' => now()->addDay()->setTime(8, 0),
        ]);
        Quest::create([
            'title' => 'Draft CV & studi kasus portfolio', 'priority' => 'hard', 'category' => 'work',
            'is_important' => true, 'progress' => 45, 'xp_reward' => $xp['hard'],
            'due_at' => now()->addDays(7)->setTime(17, 0),
        ]);
        Quest::create([
            'title' => 'Beresin lemari & donasi baju', 'priority' => 'easy', 'category' => 'personal',
            'xp_reward' => $xp['easy'],
        ]);

        // instance habit hari ini yang belum diselesaikan
        Quest::create([
            'title' => 'Baca 20 menit', 'priority' => 'normal', 'category' => 'learning',
            'xp_reward' => $xp['normal'], 'due_at' => now()->endOfDay(),
        ]);
        Quest::create([
            'title' => 'Review pengeluaran harian', 'priority' => 'easy', 'category' => 'finance',
            'xp_reward' => $xp['easy'], 'due_at' => now()->endOfDay(),
        ]);
    }

    private function seedHabits(): void
    {
        $today = now()->toDateString();
        RecurringQuest::create(['title' => 'Olahraga pagi',             'category' => 'health',   'priority' => 'normal', 'is_active' => true,  'last_spawned_date' => $today]);
        RecurringQuest::create(['title' => 'Baca 20 menit',             'category' => 'learning', 'priority' => 'normal', 'is_active' => true,  'last_spawned_date' => $today]);
        RecurringQuest::create(['title' => 'Review pengeluaran harian', 'category' => 'finance',  'priority' => 'easy',   'is_active' => true,  'last_spawned_date' => $today]);
        RecurringQuest::create(['title' => 'Jurnal malam',              'category' => 'personal', 'priority' => 'easy',   'is_active' => false, 'last_spawned_date' => now()->subDays(3)->toDateString()]);
    }

    /** 3 bulan ke belakang: mengisi ledger, chart bulanan, dan pembanding insight. */
    private function seedFinanceHistory(): void
    {
        $in  = fn ($amount, $category, $desc, $date) => FinanceEntry::create(['type' => 'in',  'amount' => $amount, 'category' => $category, 'description' => $desc, 'recorded_at' => $date]);
        $out = fn ($amount, $category, $desc, $date) => FinanceEntry::create(['type' => 'out', 'amount' => $amount, 'category' => $category, 'description' => $desc, 'recorded_at' => $date]);

        foreach ([3, 2, 1] as $m) {
            $month = now()->subMonthsNoOverflow($m);
            $day   = fn (int $d) => $month->copy()->day(min($d, $month->daysInMonth));

            $in(8500000, 'Salary', 'Gaji bulanan', $day(25));
            if ($m === 1) {
                $in(2000000, 'Freelance', 'Project landing page', $day(12));
            }
            if ($m === 3) {
                $in(1500000, 'Freelance', 'Revisi web company profile', $day(8));
            }

            $out(1200000, 'Rent', 'Kos bulanan', $day(1));
            $out(300000, 'Utilities', 'Token listrik + air', $day(5));
            $out(120000, 'Utilities', 'Internet IndiHome', $day(5));
            $out(65000, 'Subscription', 'Netflix', $day(15));
            $out(55000, 'Subscription', 'Spotify', $day(18));

            foreach ([2, 5, 8, 11, 14, 17, 20, 23, 26] as $i => $d) {
                $out(38000 + ($i % 4) * 17000, 'Food', ['Makan siang', 'Belanja warung', 'Kopi + roti', 'Dinner'][$i % 4], $day($d));
            }
            foreach ([3, 10, 17, 24] as $i => $d) {
                $out($i % 2 === 0 ? 100000 : 32000, 'Transport', $i % 2 === 0 ? 'Bensin' : 'Gojek', $day($d));
            }
            $out(60000, 'Entertainment', 'Nonton bioskop', $day(13));
            $out(58000, 'Entertainment', 'Sewa PS rental', $day(27));
            if ($m === 2) {
                $out(240000, 'Shopping', 'Sepatu lari', $day(16));
                $out(95000, 'Health', 'Obat + vitamin', $day(21));
            }
        }
    }

    /**
     * Bulan berjalan, dipatok agar HP bar budget selalu menampilkan
     * 3 kondisi: Food 63% (aman), Transport 87% (warning), Entertainment 116% (jebol).
     */
    private function seedFinanceCurrentMonth(): void
    {
        // tanggal "n hari lalu" tapi tidak pernah keluar dari bulan berjalan
        $inMonth = fn (int $back) => now()->subDays($back)->max(now()->startOfMonth());

        $out = fn ($amount, $category, $desc, $date) => FinanceEntry::create(['type' => 'out', 'amount' => $amount, 'category' => $category, 'description' => $desc, 'recorded_at' => $date]);

        $out(1200000, 'Rent', 'Kos bulanan', now()->startOfMonth());
        $out(300000, 'Utilities', 'Token listrik + air', $inMonth(20));

        // Food: total 950.000
        $out(120000, 'Food', 'Belanja mingguan', $inMonth(16));
        $out(120000, 'Food', 'Belanja mingguan', $inMonth(9));
        $out(120000, 'Food', 'Belanja mingguan', $inMonth(2));
        foreach ([15, 12, 10, 7, 4, 1] as $d) {
            $out(45000, 'Food', 'Makan siang', $inMonth($d));
        }
        foreach ([14, 11, 6, 3, 0] as $d) {
            $out(28000, 'Food', 'Kopi', $inMonth($d));
        }
        $out(180000, 'Food', 'Dinner bareng teman', $inMonth(5));

        // Transport: total 350.000
        $out(100000, 'Transport', 'Bensin', $inMonth(13));
        $out(100000, 'Transport', 'Bensin', $inMonth(3));
        foreach ([11, 8, 6, 2, 0] as $d) {
            $out(30000, 'Transport', 'Gojek', $inMonth($d));
        }

        // Entertainment: total 290.000 (melebihi limit 250.000)
        $out(100000, 'Entertainment', 'Nonton bioskop', $inMonth(8));
        $out(150000, 'Entertainment', 'Steam sale', $inMonth(4));
        $out(40000, 'Entertainment', 'Karaoke', $inMonth(1));

        // Subscription kecil (limit longgar)
        $out(55000, 'Subscription', 'Spotify', $inMonth(12));
        $out(66000, 'Subscription', 'iCloud + Google One', $inMonth(24)->max(now()->startOfMonth()));

        $out(85000, 'Health', 'Vitamin', $inMonth(7));
        $out(120000, 'Shopping', 'Kaos + celana pendek', $inMonth(10));

        // income bulan berjalan
        FinanceEntry::create(['type' => 'in', 'amount' => 750000, 'category' => 'Freelance', 'description' => 'Maintenance web klien', 'recorded_at' => $inMonth(5)]);
        if (now()->day >= 25) {
            FinanceEntry::create(['type' => 'in', 'amount' => 8500000, 'category' => 'Salary', 'description' => 'Gaji bulanan', 'recorded_at' => now()->day(25)]);
        }
    }

    private function seedBudgets(): void
    {
        Budget::create(['category' => 'Food',          'monthly_limit' => 1500000, 'icon' => '🍜']);
        Budget::create(['category' => 'Transport',     'monthly_limit' => 400000,  'icon' => '🛵']);
        Budget::create(['category' => 'Entertainment', 'monthly_limit' => 250000,  'icon' => '🎮']);
        Budget::create(['category' => 'Subscription',  'monthly_limit' => 300000,  'icon' => '📺']);
    }

    private function seedRecurringTemplates(): void
    {
        $period = now()->format('Y-m');
        $prev   = now()->subMonthNoOverflow()->format('Y-m');
        $mark   = fn (int $day) => now()->day >= $day ? $period : $prev;

        RecurringTemplate::create(['type' => 'out', 'amount' => 1200000, 'category' => 'Rent',         'description' => 'Kos bulanan',       'day_of_month' => 1,  'is_active' => true, 'last_posted_period' => $period]);
        RecurringTemplate::create(['type' => 'out', 'amount' => 120000,  'category' => 'Utilities',    'description' => 'Internet IndiHome', 'day_of_month' => 5,  'is_active' => true, 'last_posted_period' => $mark(5)]);
        RecurringTemplate::create(['type' => 'out', 'amount' => 65000,   'category' => 'Subscription', 'description' => 'Netflix',           'day_of_month' => 15, 'is_active' => true, 'last_posted_period' => $mark(15)]);
        RecurringTemplate::create(['type' => 'in',  'amount' => 8500000, 'category' => 'Salary',       'description' => 'Gaji bulanan',      'day_of_month' => 25, 'is_active' => true, 'last_posted_period' => $mark(25)]);
    }

    private function seedSavings(): void
    {
        $emergency = Saving::create(['name' => 'Dana Darurat', 'target_amount' => 20000000, 'icon' => '💰', 'color' => 'grass', 'is_active' => true, 'note' => 'Target 6x pengeluaran bulanan.']);
        $laptop    = Saving::create(['name' => 'Laptop Baru', 'target_amount' => 15000000, 'icon' => '💻', 'color' => 'sky', 'is_active' => true, 'target_date' => now()->addMonthsNoOverflow(6)]);
        $bali      = Saving::create(['name' => 'Liburan Bali', 'target_amount' => 5000000, 'icon' => '🏖', 'color' => 'corn', 'is_active' => true, 'target_date' => now()->addMonthsNoOverflow(4)]);

        $on = fn (int $monthsAgo) => now()->subMonthsNoOverflow($monthsAgo)->day(26)->toDateString();

        foreach ([5, 4, 3, 2, 1, 0] as $k) {
            SavingDeposit::create(['saving_id' => $emergency->id, 'amount' => 1000000, 'deposited_at' => $on($k), 'note' => $k === 0 ? 'Auto transfer payday' : null]);
        }
        foreach ([5, 4, 3, 2] as $k) {
            SavingDeposit::create(['saving_id' => $laptop->id, 'amount' => 500000, 'deposited_at' => $on($k)]);
        }
        SavingDeposit::create(['saving_id' => $laptop->id, 'amount' => 750000, 'deposited_at' => $on(1)]);
        SavingDeposit::create(['saving_id' => $laptop->id, 'amount' => 750000, 'deposited_at' => $on(0), 'note' => 'Bonus project']);
        foreach ([4, 3, 2, 1, 0] as $k) {
            SavingDeposit::create(['saving_id' => $bali->id, 'amount' => 250000, 'deposited_at' => $on($k)]);
        }
    }

    private function seedLibrary(): void
    {
        $items = [
            // S tier (>= 9)
            ['anime', '52991',  "Frieren: Beyond Journey's End", 'Fantasy, Adventure, Drama',   9.5, 'completed', 'Masterpiece. Pacing dan world building terbaik dekade ini.'],
            ['tv',    '1396',   'Breaking Bad',                  'Crime, Drama, Thriller',      9.3, 'completed', null],
            ['manga', '13',     'One Piece',                     'Action, Adventure, Fantasy',  9.0, 'ongoing',   null],
            // A tier (8 - 8.9)
            ['movie', '157336', 'Interstellar',                  'Sci-Fi, Drama',               8.7, 'completed', 'Nonton ke-3 kalinya tetap merinding.'],
            ['anime', '49387',  'Vinland Saga Season 2',         'Action, Drama, History',      8.5, 'completed', null],
            ['tv',    '136315', 'The Bear',                      'Comedy, Drama',               8.2, 'ongoing',   null],
            // B tier (7 - 7.9)
            ['anime', '50265',  'Spy x Family',                  'Comedy, Action, Slice of Life', 7.8, 'ongoing', null],
            ['movie', '693134', 'Dune: Part Two',                'Sci-Fi, Adventure',           7.5, 'completed', null],
            ['manga', '44347',  'Chainsaw Man',                  'Action, Horror, Supernatural', 7.2, 'ongoing',  null],
            // C tier (6 - 6.9)
            ['tv',    '84773',  'The Rings of Power',            'Fantasy, Adventure',          6.3, 'dropped',   null],
            // D tier (< 6)
            ['anime', '34566',  'Boruto: Naruto Next Generations', 'Action, Adventure',         5.0, 'dropped',   'Nostalgia doang, fillernya kebanyakan.'],
            // belum dirating
            ['movie', '872585', 'Oppenheimer',                   'Drama, History',              null, 'plan_to',  null],
            ['manga', '2',      'Berserk',                       'Action, Dark Fantasy, Horror', null, 'plan_to', null],
            ['tv',    '94605',  'Arcane',                        'Animation, Action, Drama',    null, 'plan_to',  null],
        ];

        foreach ($items as [$api, $extId, $title, $genre, $rating, $status, $review]) {
            LibraryItem::create([
                'api_type'        => $api,
                'external_id'     => $extId,
                'title'           => $title,
                'genre'           => $genre,
                'personal_rating' => $rating,
                'personal_review' => $review,
                'status'          => $status,
            ]);
        }
    }
}

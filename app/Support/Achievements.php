<?php

namespace App\Support;

use App\Models\Budget;
use App\Models\FinanceEntry;
use App\Models\LibraryItem;
use App\Models\Quest;
use App\Models\SavingDeposit;
use Illuminate\Support\Facades\DB;

/**
 * Sistem achievement lintas modul.
 * Definisi hidup di kode (mudah ditambah), status unlock di tabel
 * achievement_unlocks. evaluate() dipanggil setelah aksi bermakna
 * (selesaikan quest, kasih rating, catat transaksi, setor tabungan).
 */
class Achievements
{
    public const DEFS = [
        'first_quest' => ['icon' => '🌱', 'title' => 'Langkah Pertama', 'desc' => 'Selesaikan quest pertamamu'],
        'quest_10' => ['icon' => '⚔️', 'title' => 'Petarung Rutin', 'desc' => 'Selesaikan 10 quest'],
        'quest_50' => ['icon' => '🗡️', 'title' => 'Veteran Ladang', 'desc' => 'Selesaikan 50 quest'],
        'legendary_slayer' => ['icon' => '👑', 'title' => 'Legendary Slayer', 'desc' => 'Taklukkan quest Legendary'],
        'streak_3' => ['icon' => '🔥', 'title' => 'Menyala 3 Hari', 'desc' => 'Streak 3 hari berturut-turut'],
        'streak_7' => ['icon' => '☄️', 'title' => 'Seminggu Penuh', 'desc' => 'Streak 7 hari berturut-turut'],
        'first_gold' => ['icon' => '🪙', 'title' => 'Gold Pertama', 'desc' => 'Catat transaksi pertamamu'],
        'budget_keeper' => ['icon' => '🛡️', 'title' => 'Penjaga Budget', 'desc' => 'Pasang budget pertamamu'],
        'saver_1m' => ['icon' => '🐷', 'title' => 'Sejuta Pertama', 'desc' => 'Total tabungan tembus Rp 1.000.000'],
        'curator_10' => ['icon' => '📚', 'title' => 'Kurator', 'desc' => 'Koleksi 10 judul di Library'],
        'critic_stier' => ['icon' => '⭐', 'title' => 'Mata Kritikus', 'desc' => 'Beri rating S-Tier (9.0+)'],
        'level_5' => ['icon' => '🏆', 'title' => 'Level 5', 'desc' => 'Kumpulkan 400+ XP total'],
    ];

    /**
     * Evaluasi semua achievement yang belum terbuka.
     *
     * @return array<int, array{key:string,icon:string,title:string,desc:string}> yang BARU terbuka
     */
    public static function evaluate(): array
    {
        $unlocked = DB::table('achievement_unlocks')->pluck('key')->flip()->all();
        $fresh = [];

        foreach (self::DEFS as $key => $def) {
            if (isset($unlocked[$key])) {
                continue;
            }

            if (self::passes($key)) {
                DB::table('achievement_unlocks')->insert([
                    'key' => $key,
                    'unlocked_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $fresh[] = ['key' => $key] + $def;
            }
        }

        return $fresh;
    }

    /** Daftar lengkap untuk rak trofi: def + status + waktu unlock. */
    public static function all(): array
    {
        $unlocks = DB::table('achievement_unlocks')->pluck('unlocked_at', 'key')->all();

        return collect(self::DEFS)->map(function ($def, $key) use ($unlocks) {
            return $def + [
                'key' => $key,
                'unlocked' => isset($unlocks[$key]),
                'unlocked_at' => $unlocks[$key] ?? null,
            ];
        })->values()->all();
    }

    private static function passes(string $key): bool
    {
        return match ($key) {
            'first_quest' => Quest::where('is_completed', true)->exists(),
            'quest_10' => Quest::where('is_completed', true)->count() >= 10,
            'quest_50' => Quest::where('is_completed', true)->count() >= 50,
            'legendary_slayer' => Quest::where('is_completed', true)->where('priority', 'legendary')->exists(),
            'streak_3' => PlayerProgress::stats()['streak'] >= 3,
            'streak_7' => PlayerProgress::stats()['streak'] >= 7,
            'first_gold' => FinanceEntry::exists(),
            'budget_keeper' => Budget::exists(),
            'saver_1m' => (float) SavingDeposit::sum('amount') >= 1_000_000,
            'curator_10' => LibraryItem::count() >= 10,
            'critic_stier' => LibraryItem::where('personal_rating', '>=', 9)->exists(),
            'level_5' => PlayerProgress::stats()['level'] >= 5,
            default => false,
        };
    }
}

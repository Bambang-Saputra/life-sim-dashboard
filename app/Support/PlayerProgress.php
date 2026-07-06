<?php

namespace App\Support;

use App\Models\Quest;

/**
 * Statistik pemain diturunkan langsung dari data quest di database —
 * tidak ada state duplikat yang bisa out-of-sync (localStorage dsb).
 *
 * Level: flat 100 XP per level (konsisten dengan hero Quest page).
 * Streak: hari berurutan dengan >= 1 quest selesai, dihitung mundur
 * dari hari ini (atau kemarin, supaya streak tidak putus sebelum
 * kamu sempat menyelesaikan quest hari ini).
 */
class PlayerProgress
{
    public static function stats(): array
    {
        $totalXp = (int) Quest::where('is_completed', true)->sum('xp_reward');

        $dates = Quest::where('is_completed', true)
            ->whereNotNull('completed_at')
            ->selectRaw('DATE(completed_at) as d')
            ->distinct()
            ->orderByDesc('d')
            ->limit(400)
            ->pluck('d')
            ->all();

        [$current, $longest] = self::streaks($dates);

        return [
            'xp' => $totalXp,
            'level' => intdiv($totalXp, 100) + 1,
            'xp_into_level' => $totalXp % 100,
            'streak' => $current,
            'longest_streak' => $longest,
        ];
    }

    /** @param string[] $dates tanggal unik 'Y-m-d' terurut menurun */
    private static function streaks(array $dates): array
    {
        if ($dates === []) {
            return [0, 0];
        }

        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        // Streak berjalan: hanya hidup bila hari terakhir = hari ini/kemarin
        $current = 0;
        if ($dates[0] === $today || $dates[0] === $yesterday) {
            $current = 1;
            $cursor = \Carbon\Carbon::parse($dates[0]);
            for ($i = 1; $i < count($dates); $i++) {
                $cursor = $cursor->subDay();
                if ($dates[$i] === $cursor->toDateString()) {
                    $current++;
                } else {
                    break;
                }
            }
        }

        // Streak terpanjang sepanjang masa
        $longest = 1;
        $run = 1;
        for ($i = 1; $i < count($dates); $i++) {
            $gap = \Carbon\Carbon::parse($dates[$i - 1])->diffInDays(\Carbon\Carbon::parse($dates[$i]));
            $run = ($gap === 1) ? $run + 1 : 1;
            $longest = max($longest, $run);
        }

        return [$current, max($longest, $current)];
    }
}

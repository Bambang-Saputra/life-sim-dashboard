<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Habit harian: template quest yang otomatis muncul kembali setiap hari.
 */
class RecurringQuest extends Model
{
    protected $fillable = ['title', 'category', 'priority', 'is_active', 'last_spawned_date'];

    protected $casts = [
        'is_active' => 'boolean',
        'last_spawned_date' => 'date',
    ];

    /**
     * Spawn quest hari ini untuk semua habit aktif yang belum di-spawn.
     * Idempotent via last_spawned_date. Due = akhir hari ini.
     *
     * @return int jumlah quest yang dibuat
     */
    public static function spawnDue(): int
    {
        $today = now()->toDateString();
        $spawned = 0;

        $due = static::where('is_active', true)
            ->where(function ($q) use ($today) {
                $q->whereNull('last_spawned_date')
                    ->orWhere('last_spawned_date', '<', $today);
            })
            ->get();

        foreach ($due as $habit) {
            Quest::create([
                'title' => $habit->title,
                'description' => 'Habit harian (auto-spawn)',
                'priority' => $habit->priority,
                'category' => $habit->category,
                'due_at' => now()->endOfDay(),
                'progress' => 0,
                'is_important' => false,
                'xp_reward' => match ($habit->priority) {
                    'legendary' => 100,
                    'hard' => 50,
                    'normal' => 20,
                    default => 10,
                },
            ]);

            $habit->update(['last_spawned_date' => $today]);
            $spawned++;
        }

        return $spawned;
    }
}

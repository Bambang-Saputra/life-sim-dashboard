<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;
use App\Models\Quest;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Life-Sim Scheduled Tasks
|--------------------------------------------------------------------------
| Run cron every minute (in Laragon: php artisan schedule:work),
| atau di server: * * * * * cd /path && php artisan schedule:run
*/

// Morning notification — log overdue quests at 07:00 Asia/Jakarta
Schedule::call(function () {
    $overdue = Quest::pending()
        ->whereNotNull('due_at')
        ->where('due_at', '<', now())
        ->get();

    if ($overdue->isNotEmpty()) {
        Log::info('🌅 Morning quest reminder', [
            'overdue_count' => $overdue->count(),
            'titles'        => $overdue->pluck('title')->take(5)->toArray(),
        ]);
    }
})
->dailyAt('07:00')
->timezone('Asia/Jakarta')
->name('quest:morning-reminder')
->withoutOverlapping();

// Evening summary — log today's completion stats at 19:00
Schedule::call(function () {
    $completedToday = Quest::where('is_completed', true)
        ->whereDate('completed_at', today())
        ->count();

    $xpToday = Quest::where('is_completed', true)
        ->whereDate('completed_at', today())
        ->sum('xp_reward');

    Log::info('🌙 Evening summary', [
        'completed_today' => $completedToday,
        'xp_earned'       => $xpToday,
    ]);
})
->dailyAt('19:00')
->timezone('Asia/Jakarta')
->name('quest:evening-summary')
->withoutOverlapping();

// Weekly cleanup — auto-archive dropped library items after 30 days (Sunday 02:00)
Schedule::call(function () {
    $cutoff = now()->subDays(30);
    Log::info('🧹 Weekly library cleanup checked', ['cutoff' => $cutoff->toDateString()]);
})
->weeklyOn(0, '02:00')
->timezone('Asia/Jakarta')
->name('library:weekly-cleanup');

<?php

namespace App\Livewire;

use App\Models\Quest;
use Livewire\Component;
use Livewire\Attributes\Computed;

class QuestSummary extends Component
{
    #[Computed]
    public function topQuests()
    {
        return Quest::pending()
            ->orderByRaw('CASE WHEN due_at IS NULL THEN 1 ELSE 0 END')
            ->orderBy('due_at')
            ->limit(4)
            ->get();
    }

    #[Computed]
    public function stats(): array
    {
        return [
            'total_active'  => Quest::pending()->count(),
            'done_today'    => Quest::where('is_completed', true)->whereDate('completed_at', today())->count(),
            'overdue'       => Quest::pending()->whereNotNull('due_at')->where('due_at', '<', now())->count(),
            'important'     => Quest::pending()->where('is_important', true)->count(),
        ];
    }

    public function render()
    {
        return view('livewire.quest-summary');
    }
}

<?php

namespace App\Livewire;

use App\Models\Quest;
use App\Models\RecurringQuest;
use App\Support\Achievements;
use App\Support\PlayerProgress;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;

class QuestBoard extends Component
{
    #[Rule('required|min:3|max:255')]
    public string $title = '';

    #[Rule('nullable|string|max:500')]
    public string $description = '';

    #[Rule('nullable|string|max:2000')]
    public string $history = '';

    #[Rule('required|in:easy,normal,hard,legendary')]
    public string $priority = 'normal';

    #[Rule('nullable|string|max:50')]
    public string $category = '';

    #[Rule('nullable|date')]
    public string $due_at = '';

    #[Rule('nullable|date')]
    public string $alarm_at = '';

    #[Rule('integer|min:0|max:100')]
    public int $progress = 0;

    public bool $is_important = false;

    public string $filter    = 'all';
    public string $sortBy    = 'due_at';
    public bool   $showForm  = false;
    public ?int   $editingId = null;

    public function mount(): void
    {
        // Safety net habit harian: spawn saat halaman dibuka
        // (scheduler 00:05 adalah jalur utama)
        RecurringQuest::spawnDue();
    }

    /** XP, level, dan streak — diturunkan dari database, bukan localStorage. */
    public function getPlayerStatsProperty(): array
    {
        return PlayerProgress::stats();
    }

    public function getQuestsProperty()
    {
        return Quest::query()
            ->when($this->filter === 'pending',   fn($q) => $q->pending())
            ->when($this->filter === 'completed', fn($q) => $q->where('is_completed', true))
            ->when($this->filter === 'important', fn($q) => $q->where('is_important', true))
            ->orderBy(
                $this->sortBy === 'priority'
                    ? DB::raw("FIELD(priority, 'legendary','hard','normal','easy')")
                    : $this->sortBy
            )
            ->get();
    }

    public function toggleComplete(int $questId): void
    {
        $quest = Quest::findOrFail($questId);
        $newCompleted = !$quest->is_completed;

        $quest->update([
            'is_completed' => $newCompleted,
            'completed_at' => $newCompleted ? now() : null,
            'progress'     => $newCompleted ? 100 : $quest->progress,
        ]);

        if ($newCompleted) {
            $this->dispatch('quest-completed', [
                'title'    => $quest->title,
                'xpReward' => $quest->xp_reward,
            ]);

            foreach (Achievements::evaluate() as $a) {
                $this->dispatch('achievement-unlocked', icon: $a['icon'], title: $a['title'], desc: $a['desc']);
            }
        }
    }

    public function toggleImportant(int $questId): void
    {
        $quest = Quest::findOrFail($questId);
        $quest->update(['is_important' => !$quest->is_important]);
    }

    public function updateProgress(int $questId, int $progress): void
    {
        $progress = max(0, min(100, $progress));
        Quest::findOrFail($questId)->update([
            'progress'     => $progress,
            'is_completed' => $progress >= 100,
            'completed_at' => $progress >= 100 ? now() : null,
        ]);
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'        => $this->title,
            'description'  => $this->description ?: null,
            'history'      => $this->history ?: null,
            'priority'     => $this->priority,
            'category'     => $this->category ?: null,
            'due_at'       => $this->due_at ?: null,
            'alarm_at'     => $this->alarm_at
                ? $this->alarm_at
                : ($this->due_at ? date('Y-m-d H:i:s', strtotime($this->due_at) - 1800) : null),
            'progress'     => $this->progress,
            'is_important' => $this->is_important,
            'xp_reward'    => match($this->priority) {
                'legendary' => 100,
                'hard'      => 50,
                'normal'    => 20,
                default     => 10,
            },
        ];

        if ($this->editingId) {
            Quest::findOrFail($this->editingId)->update($data);
        } else {
            Quest::create($data);
        }

        $this->resetForm();
        $this->dispatch('quest-saved');
    }

    public function delete(int $questId): void
    {
        Quest::findOrFail($questId)->delete();
        $this->dispatch('quest-deleted');
    }

    public function startEdit(int $questId): void
    {
        $quest = Quest::findOrFail($questId);

        $this->editingId    = $questId;
        $this->title        = $quest->title;
        $this->description  = $quest->description ?? '';
        $this->history      = $quest->history ?? '';
        $this->priority     = $quest->priority;
        $this->category     = $quest->category ?? '';
        $this->due_at       = $quest->due_at?->format('Y-m-d\TH:i') ?? '';
        $this->alarm_at     = $quest->alarm_at?->format('Y-m-d\TH:i') ?? '';
        $this->progress     = (int) $quest->progress;
        $this->is_important = (bool) $quest->is_important;
        $this->showForm     = true;
    }

    public function cancelForm(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->title        = '';
        $this->description  = '';
        $this->history      = '';
        $this->priority     = 'normal';
        $this->category     = '';
        $this->due_at       = '';
        $this->alarm_at     = '';
        $this->progress     = 0;
        $this->is_important = false;
        $this->editingId    = null;
        $this->showForm     = false;
    }

    public function render()
    {
        return view('livewire.quest-board');
    }
}

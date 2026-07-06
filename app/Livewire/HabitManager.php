<?php

namespace App\Livewire;

use App\Models\RecurringQuest;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;

class HabitManager extends Component
{
    #[Rule('required|string|min:3|max:255')]
    public string $title = '';

    #[Rule('nullable|string|max:50')]
    public string $category = '';

    #[Rule('required|in:easy,normal,hard,legendary')]
    public string $priority = 'easy';

    public bool $showForm = false;
    public ?int $editingId = null;

    #[Computed]
    public function habits()
    {
        $today = now()->toDateString();

        return RecurringQuest::orderByDesc('is_active')->orderBy('title')->get()
            ->map(function ($h) use ($today) {
                $h->spawned_today = optional($h->last_spawned_date)->toDateString() === $today;

                return $h;
            });
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title' => trim($this->title),
            'category' => $this->category ?: null,
            'priority' => $this->priority,
        ];

        if ($this->editingId) {
            RecurringQuest::findOrFail($this->editingId)->update($data);
        } else {
            RecurringQuest::create($data + ['is_active' => true]);
            // Habit baru langsung muncul sebagai quest hari ini
            RecurringQuest::spawnDue();
        }

        $this->resetForm();
        $this->dispatch('habit-saved');
    }

    public function toggleActive(int $id): void
    {
        $h = RecurringQuest::findOrFail($id);
        $h->update(['is_active' => ! $h->is_active]);
    }

    public function startEdit(int $id): void
    {
        $h = RecurringQuest::findOrFail($id);
        $this->editingId = $id;
        $this->title = $h->title;
        $this->category = $h->category ?? '';
        $this->priority = $h->priority;
        $this->showForm = true;
    }

    public function delete(int $id): void
    {
        RecurringQuest::findOrFail($id)->delete();
    }

    public function cancelForm(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->title = '';
        $this->category = '';
        $this->priority = 'easy';
        $this->editingId = null;
        $this->showForm = false;
    }

    public function render()
    {
        return view('livewire.habit-manager');
    }
}

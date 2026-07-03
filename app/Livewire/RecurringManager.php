<?php

namespace App\Livewire;

use App\Models\RecurringTemplate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;

class RecurringManager extends Component
{
    #[Rule('required|in:in,out')]
    public string $type = 'out';

    #[Rule('required|numeric|min:100|max:999999999999')]
    public string $amount = '';

    #[Rule('required|string|max:100')]
    public string $category = '';

    #[Rule('nullable|string|max:255')]
    public string $description = '';

    #[Rule('required|integer|min:1|max:31')]
    public int $day_of_month = 1;

    public bool $showForm = false;
    public ?int $editingId = null;
    public int $justPosted = 0;

    public function mount(): void
    {
        // Safety net: personal app jarang menjalankan scheduler,
        // jadi posting jatuh tempo juga dicek setiap halaman Finance dibuka.
        $this->justPosted = RecurringTemplate::postDue();
    }

    #[Computed]
    public function templates()
    {
        $period = now()->format('Y-m');

        return RecurringTemplate::orderBy('day_of_month')->get()
            ->map(function ($t) use ($period) {
                $t->posted_this_month = $t->last_posted_period === $period;

                return $t;
            });
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'type' => $this->type,
            'amount' => $this->amount,
            'category' => trim($this->category),
            'description' => $this->description ?: null,
            'day_of_month' => $this->day_of_month,
        ];

        if ($this->editingId) {
            RecurringTemplate::findOrFail($this->editingId)->update($data);
        } else {
            RecurringTemplate::create($data + ['is_active' => true]);
            // Template baru yang tanggalnya sudah lewat bulan ini → langsung posting
            $this->justPosted += RecurringTemplate::postDue();
        }

        $this->resetForm();
        $this->dispatch('recurring-saved');
    }

    public function toggleActive(int $id): void
    {
        $t = RecurringTemplate::findOrFail($id);
        $t->update(['is_active' => ! $t->is_active]);
    }

    public function startEdit(int $id): void
    {
        $t = RecurringTemplate::findOrFail($id);
        $this->editingId = $id;
        $this->type = $t->type;
        $this->amount = (string) $t->amount;
        $this->category = $t->category;
        $this->description = $t->description ?? '';
        $this->day_of_month = $t->day_of_month;
        $this->showForm = true;
    }

    public function delete(int $id): void
    {
        RecurringTemplate::findOrFail($id)->delete();
    }

    public function cancelForm(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->type = 'out';
        $this->amount = '';
        $this->category = '';
        $this->description = '';
        $this->day_of_month = 1;
        $this->editingId = null;
        $this->showForm = false;
    }

    public function render()
    {
        return view('livewire.recurring-manager');
    }
}

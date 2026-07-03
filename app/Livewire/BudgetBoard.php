<?php

namespace App\Livewire;

use App\Models\Budget;
use App\Models\FinanceEntry;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;

class BudgetBoard extends Component
{
    #[Rule('required|string|max:100')]
    public string $category = '';

    #[Rule('required|numeric|min:1000|max:999999999999')]
    public string $monthly_limit = '';

    #[Rule('nullable|string|max:8')]
    public string $icon = '🎯';

    public bool $showForm = false;
    public ?int $editingId = null;

    /**
     * Budget + realisasi bulan berjalan, satu query agregat
     * (bukan N query per budget).
     */
    #[Computed]
    public function budgets()
    {
        $spentByCategory = FinanceEntry::where('type', 'out')
            ->whereYear('recorded_at', now()->year)
            ->whereMonth('recorded_at', now()->month)
            ->selectRaw('LOWER(category) as cat, SUM(amount) as total')
            ->groupBy('cat')
            ->pluck('total', 'cat');

        return Budget::orderBy('category')->get()->map(function ($b) use ($spentByCategory) {
            $spent = (float) ($spentByCategory[mb_strtolower($b->category)] ?? 0);
            $limit = (float) $b->monthly_limit;
            $percent = $limit > 0 ? ($spent / $limit) * 100 : 0;

            $b->spent = $spent;
            $b->percent = min(100, (int) round($percent));
            $b->raw_percent = round($percent);
            $b->remaining = $limit - $spent;
            $b->status = $percent >= 100 ? 'over' : ($percent >= 80 ? 'warning' : 'safe');

            return $b;
        });
    }

    /** Saran kategori: kategori expense yang sudah dipakai tapi belum di-budget. */
    #[Computed]
    public function unbudgetedCategories(): array
    {
        $budgeted = Budget::pluck('category')->map(fn ($c) => mb_strtolower($c))->all();

        return FinanceEntry::where('type', 'out')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->filter(fn ($r) => ! in_array(mb_strtolower($r->category), $budgeted))
            ->pluck('category')
            ->take(8)
            ->values()
            ->all();
    }

    public function save(): void
    {
        $this->validate();

        // Cegah duplikat case-insensitive (kecuali sedang mengedit baris yang sama)
        $existing = Budget::whereRaw('LOWER(category) = ?', [mb_strtolower($this->category)])->first();
        if ($existing && $existing->id !== $this->editingId) {
            $this->addError('category', 'Kategori ini sudah punya budget.');

            return;
        }

        $data = [
            'category' => trim($this->category),
            'monthly_limit' => $this->monthly_limit,
            'icon' => $this->icon ?: '🎯',
        ];

        if ($this->editingId) {
            Budget::findOrFail($this->editingId)->update($data);
        } else {
            Budget::create($data);
        }

        $this->resetForm();
        $this->dispatch('budget-saved');
    }

    public function startEdit(int $id): void
    {
        $b = Budget::findOrFail($id);
        $this->editingId = $id;
        $this->category = $b->category;
        $this->monthly_limit = (string) $b->monthly_limit;
        $this->icon = $b->icon;
        $this->showForm = true;
    }

    public function delete(int $id): void
    {
        Budget::findOrFail($id)->delete();
    }

    public function cancelForm(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->category = '';
        $this->monthly_limit = '';
        $this->icon = '🎯';
        $this->editingId = null;
        $this->showForm = false;
    }

    public function render()
    {
        return view('livewire.budget-board');
    }
}

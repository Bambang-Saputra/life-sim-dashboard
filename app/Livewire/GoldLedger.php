<?php

namespace App\Livewire;

use App\Models\FinanceEntry;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Computed;

class GoldLedger extends Component
{
    #[Rule('required|in:in,out')]
    public string $type = 'out';

    #[Rule('required|numeric|min:0.01|max:999999999')]
    public string $amount = '';

    #[Rule('required|string|max:100')]
    public string $category = '';

    #[Rule('nullable|string|max:255')]
    public string $description = '';

    #[Rule('required|date')]
    public string $recorded_at = '';

    public bool  $showForm    = false;
    public ?int  $editingId   = null;
    public int   $viewYear    = 0;
    public int   $viewMonth   = 0;
    public string $filterType = 'all';

    // Pencarian & rentang tanggal bebas (mengalahkan navigasi bulan bila diisi)
    public string $search   = '';
    public string $dateFrom = '';
    public string $dateTo   = '';

    public function mount(): void
    {
        $this->viewYear  = now()->year;
        $this->viewMonth = now()->month;
        $this->recorded_at = now()->format('Y-m-d');
    }

    /** Apakah filter lanjutan (search / rentang tanggal) sedang aktif? */
    #[Computed]
    public function isFiltering(): bool
    {
        return trim($this->search) !== '' || $this->dateFrom !== '' || $this->dateTo !== '';
    }

    #[Computed]
    public function entries()
    {
        $term = trim($this->search);
        $usingRange = $this->dateFrom !== '' || $this->dateTo !== '';

        return FinanceEntry::query()
            // Rentang tanggal bebas mengalahkan navigasi bulan
            ->when(! $usingRange, fn ($q) => $q->inMonth($this->viewYear, $this->viewMonth))
            ->when($this->dateFrom !== '', fn ($q) => $q->whereDate('recorded_at', '>=', $this->dateFrom))
            ->when($this->dateTo !== '', fn ($q) => $q->whereDate('recorded_at', '<=', $this->dateTo))
            ->when($term !== '', function ($q) use ($term) {
                $like = '%'.str_replace(['%', '_'], ['\\%', '\\_'], $term).'%';
                $q->where(fn ($w) => $w->where('category', 'like', $like)
                    ->orWhere('description', 'like', $like));
            })
            ->when($this->filterType !== 'all', fn ($q) => $q->where('type', $this->filterType))
            ->orderByDesc('recorded_at')
            ->orderByDesc('created_at')
            ->limit(200)
            ->get();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->dateFrom = '';
        $this->dateTo = '';
    }

    #[Computed]
    public function summary(): array
    {
        return FinanceEntry::monthlySummary($this->viewYear, $this->viewMonth);
    }

    #[Computed]
    public function netBalance(): float
    {
        return FinanceEntry::netBalance();
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'type'        => $this->type,
            'amount'      => $this->amount,
            'category'    => $this->category,
            'description' => $this->description ?: null,
            'recorded_at' => $this->recorded_at,
        ];

        if ($this->editingId) {
            FinanceEntry::findOrFail($this->editingId)->update($data);
        } else {
            FinanceEntry::create($data);
        }

        $this->resetForm();
        $this->dispatch('finance-entry-saved');

        foreach (\App\Support\Achievements::evaluate() as $a) {
            $this->dispatch('achievement-unlocked', icon: $a['icon'], title: $a['title'], desc: $a['desc']);
        }

    }

    public function startEdit(int $id): void
    {
        $entry = FinanceEntry::findOrFail($id);

        $this->editingId   = $id;
        $this->type        = $entry->type;
        $this->amount      = (string) $entry->amount;
        $this->category    = $entry->category;
        $this->description = $entry->description ?? '';
        $this->recorded_at = $entry->recorded_at->format('Y-m-d');
        $this->showForm    = true;
    }

    public function delete(int $id): void
    {
        FinanceEntry::findOrFail($id)->delete();
        $this->dispatch('finance-entry-deleted');
    }

    public function prevMonth(): void
    {
        if ($this->viewMonth === 1) {
            $this->viewMonth = 12;
            $this->viewYear--;
        } else {
            $this->viewMonth--;
        }
    }

    public function nextMonth(): void
    {
        if ($this->viewMonth === 12) {
            $this->viewMonth = 1;
            $this->viewYear++;
        } else {
            $this->viewMonth++;
        }
    }

    private function resetForm(): void
    {
        $this->type        = 'out';
        $this->amount      = '';
        $this->category    = '';
        $this->description = '';
        $this->recorded_at = now()->format('Y-m-d');
        $this->editingId   = null;
        $this->showForm    = false;
    }

    public function render()
    {
        return view('livewire.gold-ledger');
    }
}

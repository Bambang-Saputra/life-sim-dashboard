<?php

namespace App\Livewire;

use App\Models\Budget;
use App\Models\FinanceEntry;
use App\Models\Saving;
use App\Models\SavingDeposit;
use Livewire\Component;
use Livewire\Attributes\Computed;

class FinanceSummary extends Component
{
    #[Computed]
    public function netBalance(): float
    {
        return FinanceEntry::netBalance();
    }

    /**
     * Ringkasan status budget bulan berjalan untuk chip alert di dashboard.
     * Return null bila belum ada budget terpasang.
     */
    #[Computed]
    public function budgetAlert(): ?array
    {
        $budgets = Budget::all();
        if ($budgets->isEmpty()) {
            return null;
        }

        $spent = FinanceEntry::where('type', 'out')
            ->whereYear('recorded_at', now()->year)
            ->whereMonth('recorded_at', now()->month)
            ->selectRaw('LOWER(category) as cat, SUM(amount) as total')
            ->groupBy('cat')
            ->pluck('total', 'cat');

        $over = 0;
        $warn = 0;
        $worst = null;
        $worstPct = 0;
        foreach ($budgets as $b) {
            $limit = (float) $b->monthly_limit;
            if ($limit <= 0) {
                continue;
            }
            $pct = ((float) ($spent[mb_strtolower($b->category)] ?? 0) / $limit) * 100;
            if ($pct >= 100) {
                $over++;
            } elseif ($pct >= 80) {
                $warn++;
            }
            if ($pct > $worstPct) {
                $worstPct = $pct;
                $worst = $b->category;
            }
        }

        if ($over > 0) {
            return ['tone' => 'danger', 'text' => $over.' budget jebol: '.ucfirst($worst).' '.round($worstPct).'%'];
        }
        if ($warn > 0) {
            return ['tone' => 'warning', 'text' => $warn.' budget hampir habis: '.ucfirst($worst).' '.round($worstPct).'%'];
        }

        return ['tone' => 'success', 'text' => 'Semua budget aman ('.$budgets->count().' kategori)'];
    }

    #[Computed]
    public function thisMonth(): array
    {
        return FinanceEntry::monthlySummary(now()->year, now()->month);
    }

    #[Computed]
    public function recentEntries()
    {
        return FinanceEntry::latest('recorded_at')->latest('id')->limit(4)->get();
    }

    #[Computed]
    public function totalSavings(): float
    {
        return (float) SavingDeposit::sum('amount');
    }

    #[Computed]
    public function activeSavingsCount(): int
    {
        return Saving::where('is_active', true)->count();
    }

    #[Computed]
    public function chartData(): array
    {
        // 7 days income vs expense
        $days = [];
        $income = [];
        $expense = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $days[] = $date->format('d M');

            $income[] = (float) FinanceEntry::where('type', 'in')
                ->whereDate('recorded_at', $date)
                ->sum('amount');

            $expense[] = (float) FinanceEntry::where('type', 'out')
                ->whereDate('recorded_at', $date)
                ->sum('amount');
        }

        return compact('days', 'income', 'expense');
    }

    public function render()
    {
        return view('livewire.finance-summary');
    }
}

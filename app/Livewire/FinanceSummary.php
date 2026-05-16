<?php

namespace App\Livewire;

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

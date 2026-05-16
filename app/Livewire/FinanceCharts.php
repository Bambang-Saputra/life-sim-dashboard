<?php

namespace App\Livewire;

use App\Models\FinanceEntry;
use Livewire\Component;
use Livewire\Attributes\Computed;

class FinanceCharts extends Component
{
    public int $viewYear  = 0;
    public int $viewMonth = 0;

    public function mount(): void
    {
        $this->viewYear  = now()->year;
        $this->viewMonth = now()->month;
    }

    #[Computed]
    public function dailyData(): array
    {
        // Daily income/expense for selected month
        $daysInMonth = \Carbon\Carbon::create($this->viewYear, $this->viewMonth)->daysInMonth;
        $days = [];
        $income = [];
        $expense = [];

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $days[] = sprintf('%02d', $d);
            $date = sprintf('%d-%02d-%02d', $this->viewYear, $this->viewMonth, $d);

            $income[] = (float) FinanceEntry::where('type', 'in')
                ->whereDate('recorded_at', $date)
                ->sum('amount');

            $expense[] = (float) FinanceEntry::where('type', 'out')
                ->whereDate('recorded_at', $date)
                ->sum('amount');
        }

        return compact('days', 'income', 'expense');
    }

    #[Computed]
    public function categoryBreakdown(): array
    {
        return FinanceEntry::inMonth($this->viewYear, $this->viewMonth)
            ->where('type', 'out')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->map(fn($r) => ['category' => $r->category, 'total' => (float) $r->total])
            ->toArray();
    }

    #[Computed]
    public function monthlyTrend(): array
    {
        // last 6 months trend
        $months = [];
        $net = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $months[] = $d->format('M');
            $sum = FinanceEntry::monthlySummary($d->year, $d->month);
            $net[] = (float) $sum['balance'];
        }
        return compact('months', 'net');
    }

    public function prevMonth(): void
    {
        if ($this->viewMonth === 1) { $this->viewMonth = 12; $this->viewYear--; }
        else { $this->viewMonth--; }
    }

    public function nextMonth(): void
    {
        if ($this->viewMonth === 12) { $this->viewMonth = 1; $this->viewYear++; }
        else { $this->viewMonth++; }
    }

    public function render()
    {
        return view('livewire.finance-charts');
    }
}

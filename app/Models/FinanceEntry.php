<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinanceEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'amount',
        'category',
        'description',
        'recorded_at',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'recorded_at' => 'date',
    ];

    public function scopeIncome($query)
    {
        return $query->where('type', 'in');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'out');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereYear('recorded_at', now()->year)
                     ->whereMonth('recorded_at', now()->month);
    }

    public function scopeInMonth($query, int $year, int $month)
    {
        return $query->whereYear('recorded_at', $year)
                     ->whereMonth('recorded_at', $month);
    }

    public static function netBalance(): float
    {
        $income  = static::where('type', 'in')->sum('amount');
        $expense = static::where('type', 'out')->sum('amount');
        return (float) ($income - $expense);
    }

    public static function monthlySummary(int $year, int $month): array
    {
        $entries = static::inMonth($year, $month)->get();
        return [
            'income'  => $entries->where('type', 'in')->sum('amount'),
            'expense' => $entries->where('type', 'out')->sum('amount'),
            'balance' => $entries->where('type', 'in')->sum('amount')
                       - $entries->where('type', 'out')->sum('amount'),
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = ['category', 'monthly_limit', 'icon'];

    protected $casts = [
        'monthly_limit' => 'decimal:2',
    ];

    /**
     * Total pengeluaran bulan berjalan untuk kategori budget ini
     * (case-insensitive supaya "Food" dan "food" dihitung sama).
     */
    public function spentThisMonth(): float
    {
        return (float) FinanceEntry::where('type', 'out')
            ->whereYear('recorded_at', now()->year)
            ->whereMonth('recorded_at', now()->month)
            ->whereRaw('LOWER(category) = ?', [mb_strtolower($this->category)])
            ->sum('amount');
    }
}

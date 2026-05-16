<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Saving extends Model
{
    protected $fillable = [
        'name', 'target_amount', 'target_date', 'icon', 'color', 'note', 'is_active',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'target_date'   => 'date',
        'is_active'     => 'boolean',
    ];

    public function deposits(): HasMany
    {
        return $this->hasMany(SavingDeposit::class);
    }

    public function getCurrentAmountAttribute(): float
    {
        return (float) $this->deposits()->sum('amount');
    }

    public function getProgressPercentAttribute(): int
    {
        if (!$this->target_amount || $this->target_amount <= 0) return 0;
        return min(100, (int) round(($this->current_amount / $this->target_amount) * 100));
    }
}

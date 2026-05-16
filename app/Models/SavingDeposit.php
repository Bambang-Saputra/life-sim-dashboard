<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavingDeposit extends Model
{
    protected $fillable = ['saving_id', 'amount', 'deposited_at', 'note'];

    protected $casts = [
        'amount'       => 'decimal:2',
        'deposited_at' => 'date',
    ];

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Saving::class, 'saving_id');
    }
}

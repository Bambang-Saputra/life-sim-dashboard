<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecurringTemplate extends Model
{
    protected $fillable = [
        'type', 'amount', 'category', 'description',
        'day_of_month', 'is_active', 'last_posted_period',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'day_of_month' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Posting semua template yang sudah jatuh tempo bulan ini.
     * Idempotent: last_posted_period ('YYYY-MM') menjamin satu template
     * hanya menghasilkan satu FinanceEntry per bulan, berapa kali pun
     * fungsi ini dipanggil (scheduler + safety net saat halaman dibuka).
     *
     * @return int jumlah transaksi yang diposting
     */
    public static function postDue(): int
    {
        $now = now();
        $period = $now->format('Y-m');
        $posted = 0;

        $due = static::where('is_active', true)
            ->where(function ($q) use ($period) {
                $q->whereNull('last_posted_period')
                    ->orWhere('last_posted_period', '!=', $period);
            })
            ->get();

        foreach ($due as $tpl) {
            // Tanggal efektif: clamp ke hari terakhir bulan (31 → 28/29/30)
            $effectiveDay = min($tpl->day_of_month, $now->daysInMonth);
            if ($now->day < $effectiveDay) {
                continue; // belum jatuh tempo bulan ini
            }

            FinanceEntry::create([
                'type' => $tpl->type,
                'amount' => $tpl->amount,
                'category' => $tpl->category,
                'description' => trim(($tpl->description ?: ucfirst($tpl->category)).' (auto-recurring)'),
                'recorded_at' => $now->copy()->setDay($effectiveDay)->toDateString(),
            ]);

            $tpl->update(['last_posted_period' => $period]);
            $posted++;
        }

        return $posted;
    }
}

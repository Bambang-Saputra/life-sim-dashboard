<?php

namespace App\Livewire;

use App\Models\Budget;
use App\Models\FinanceEntry;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * Insight otomatis: membandingkan bulan berjalan dengan bulan lalu
 * pada periode yang adil (MTD vs MTD), lalu menerjemahkannya
 * jadi kalimat yang bisa langsung ditindaklanjuti.
 */
class FinanceInsights extends Component
{
    private function expenseBetween($start, $end): float
    {
        return (float) FinanceEntry::where('type', 'out')
            ->whereBetween('recorded_at', [$start->toDateString(), $end->toDateString()])
            ->sum('amount');
    }

    #[Computed]
    public function insights(): array
    {
        $now = now();
        $items = [];

        // ── Periode adil: tanggal 1 s/d hari-ke-N, dua bulan ──
        $thisStart = $now->copy()->startOfMonth();
        $lastStart = $now->copy()->subMonthNoOverflow()->startOfMonth();
        $lastSameDay = $lastStart->copy()->addDays(
            min($now->day, $lastStart->daysInMonth) - 1
        );

        $expenseMtd = $this->expenseBetween($thisStart, $now);
        $expenseLastMtd = $this->expenseBetween($lastStart, $lastSameDay);

        // ── 1. Tren pengeluaran vs bulan lalu ──
        if ($expenseLastMtd > 0) {
            $delta = (($expenseMtd - $expenseLastMtd) / $expenseLastMtd) * 100;
            if ($delta >= 15) {
                $items[] = [
                    'tone' => $delta >= 40 ? 'danger' : 'warning',
                    'icon' => '📈',
                    'title' => 'Pengeluaran naik '.round($delta).'%',
                    'body' => 'Sampai tanggal '.$now->day.', kamu sudah keluar Rp '.number_format($expenseMtd, 0, ',', '.').
                        ' — bulan lalu di titik yang sama baru Rp '.number_format($expenseLastMtd, 0, ',', '.').'.',
                ];
            } elseif ($delta <= -15) {
                $items[] = [
                    'tone' => 'success',
                    'icon' => '🌱',
                    'title' => 'Hemat '.abs(round($delta)).'% dari bulan lalu',
                    'body' => 'Pertahankan! Pengeluaranmu Rp '.number_format($expenseLastMtd - $expenseMtd, 0, ',', '.').
                        ' lebih rendah dibanding periode yang sama bulan lalu.',
                ];
            }
        }

        // ── 2. Kategori dengan lonjakan terbesar ──
        $catNow = FinanceEntry::where('type', 'out')
            ->whereBetween('recorded_at', [$thisStart->toDateString(), $now->toDateString()])
            ->selectRaw('LOWER(category) as cat, SUM(amount) as total')
            ->groupBy('cat')->pluck('total', 'cat');
        $catLast = FinanceEntry::where('type', 'out')
            ->whereBetween('recorded_at', [$lastStart->toDateString(), $lastSameDay->toDateString()])
            ->selectRaw('LOWER(category) as cat, SUM(amount) as total')
            ->groupBy('cat')->pluck('total', 'cat');

        $topCat = null;
        $topJump = 0;
        foreach ($catNow as $cat => $total) {
            $jump = (float) $total - (float) ($catLast[$cat] ?? 0);
            if ($jump > $topJump) {
                $topJump = $jump;
                $topCat = $cat;
            }
        }
        if ($topCat !== null && $topJump >= 100000) {
            $items[] = [
                'tone' => 'warning',
                'icon' => '🔍',
                'title' => ucfirst($topCat).' melonjak paling besar',
                'body' => 'Naik Rp '.number_format($topJump, 0, ',', '.').
                    ' dibanding periode sama bulan lalu. Cek transaksinya di ledger.',
            ];
        }

        // ── 3. Rata-rata harian + proyeksi akhir bulan ──
        $incomeMonth = (float) FinanceEntry::where('type', 'in')
            ->whereYear('recorded_at', $now->year)->whereMonth('recorded_at', $now->month)
            ->sum('amount');
        if ($expenseMtd > 0 && $now->day >= 3) {
            $dailyAvg = $expenseMtd / $now->day;
            $projection = $dailyAvg * $now->daysInMonth;
            $tone = ($incomeMonth > 0 && $projection > $incomeMonth) ? 'danger' : 'calm';
            $items[] = [
                'tone' => $tone,
                'icon' => '🔮',
                'title' => 'Proyeksi akhir bulan Rp '.number_format($projection, 0, ',', '.'),
                'body' => 'Rata-rata Rp '.number_format($dailyAvg, 0, ',', '.').' per hari.'.
                    ($tone === 'danger'
                        ? ' Awas: proyeksi MELEBIHI income bulan ini (Rp '.number_format($incomeMonth, 0, ',', '.').').'
                        : ($incomeMonth > 0
                            ? ' Masih di bawah income bulan ini — aman.'
                            : '')),
            ];
        }

        // ── 4. Rasio pengeluaran terhadap income ──
        $expenseMonth = (float) FinanceEntry::where('type', 'out')
            ->whereYear('recorded_at', $now->year)->whereMonth('recorded_at', $now->month)
            ->sum('amount');
        if ($incomeMonth > 0) {
            $ratio = ($expenseMonth / $incomeMonth) * 100;
            $items[] = [
                'tone' => $ratio > 90 ? 'danger' : ($ratio > 60 ? 'warning' : 'success'),
                'icon' => '⚖️',
                'title' => 'Expense '.round($ratio).'% dari income',
                'body' => $ratio > 90
                    ? 'Hampir semua gold bulan ini terpakai. Saatnya rem darurat.'
                    : ($ratio > 60
                        ? 'Masih wajar, tapi ruang menabungmu menipis.'
                        : 'Sehat! Sisa '.(100 - round($ratio)).'% bisa masuk tabungan.'),
            ];
        }

        // ── 5. Status budget (nyambung ke Budget Board) ──
        $budgets = Budget::all();
        if ($budgets->isNotEmpty()) {
            $spentMap = $catNow; // sudah LOWER(category) => total MTD
            $over = 0;
            $warn = 0;
            $worst = null;
            $worstPct = 0;
            foreach ($budgets as $b) {
                $limit = (float) $b->monthly_limit;
                if ($limit <= 0) {
                    continue;
                }
                $pct = ((float) ($spentMap[mb_strtolower($b->category)] ?? 0) / $limit) * 100;
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
                $items[] = [
                    'tone' => 'danger',
                    'icon' => '🚨',
                    'title' => $over.' budget jebol',
                    'body' => ucfirst($worst).' paling parah ('.round($worstPct).'% dari limit). Lihat Budget Board di bawah.',
                ];
            } elseif ($warn > 0) {
                $items[] = [
                    'tone' => 'warning',
                    'icon' => '⏳',
                    'title' => $warn.' budget hampir habis',
                    'body' => ucfirst($worst).' sudah '.round($worstPct).'% dari limit bulanan.',
                ];
            } else {
                $items[] = [
                    'tone' => 'success',
                    'icon' => '🛡️',
                    'title' => 'Semua budget aman',
                    'body' => 'Belum ada kategori yang mendekati limit. Mantap.',
                ];
            }
        }

        return array_slice($items, 0, 4);
    }

    public function render()
    {
        return view('livewire.finance-insights');
    }
}

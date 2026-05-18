<?php

namespace App\Http\Controllers;

use App\Models\FinanceEntry;
use App\Models\Saving;
use App\Models\SavingDeposit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FinanceExportController extends Controller
{
    /**
     * Export Finance entries ke CSV (Excel-compatible).
     * URL: /finance/export/csv?year=2026&month=5  (omit untuk semua)
     */
    public function csv(Request $request): StreamedResponse
    {
        [$year, $month, $entries, $savings] = $this->fetchData($request);

        $rangeLabel  = $month
            ? Carbon::create($year, $month)->format('F-Y')
            : 'all-time';
        $filename = "life-sim-finance-{$rangeLabel}.csv";

        return response()->streamDownload(function () use ($entries, $savings) {
            // BOM untuk UTF-8 supaya Excel baca bahasa Indonesia benar
            echo "\xEF\xBB\xBF";

            $out = fopen('php://output', 'w');

            // ─── SECTION 1: Transaction Entries ───
            fputcsv($out, ['LIFE-SIM DASHBOARD — Finance Export']);
            fputcsv($out, ['Generated', now()->format('Y-m-d H:i')]);
            fputcsv($out, []);
            fputcsv($out, ['=== TRANSAKSI ===']);
            fputcsv($out, ['Tanggal', 'Tipe', 'Kategori', 'Jumlah (Rp)', 'Deskripsi']);

            $totalIn  = 0;
            $totalOut = 0;
            foreach ($entries as $e) {
                $amt = (float) $e->amount;
                if ($e->type === 'in') { $totalIn += $amt; } else { $totalOut += $amt; }
                fputcsv($out, [
                    $e->recorded_at->format('Y-m-d'),
                    $e->type === 'in' ? 'INCOME' : 'EXPENSE',
                    $e->category,
                    ($e->type === 'in' ? '+' : '-') . number_format($amt, 0, ',', '.'),
                    $e->description ?? '',
                ]);
            }

            fputcsv($out, []);
            fputcsv($out, ['=== RINGKASAN ===']);
            fputcsv($out, ['Total Income',  '+Rp ' . number_format($totalIn, 0, ',', '.')]);
            fputcsv($out, ['Total Expense', '-Rp ' . number_format($totalOut, 0, ',', '.')]);
            fputcsv($out, ['Saldo Bersih',  'Rp ' . number_format($totalIn - $totalOut, 0, ',', '.')]);

            // ─── SECTION 2: Savings ───
            if ($savings->isNotEmpty()) {
                fputcsv($out, []);
                fputcsv($out, ['=== TABUNGAN ===']);
                fputcsv($out, ['Nama', 'Saldo (Rp)', 'Target (Rp)', 'Progress', 'Target Tanggal', 'Catatan']);

                $totalSaved = 0;
                foreach ($savings as $s) {
                    $current = (float) ($s->current_amount_raw ?? $s->current_amount);
                    $totalSaved += $current;
                    $target  = (float) $s->target_amount;
                    $percent = $target > 0 ? round(($current / $target) * 100) . '%' : '-';
                    fputcsv($out, [
                        $s->icon . ' ' . $s->name,
                        number_format($current, 0, ',', '.'),
                        $target > 0 ? number_format($target, 0, ',', '.') : '-',
                        $percent,
                        $s->target_date?->format('Y-m-d') ?? '-',
                        $s->note ?? '',
                    ]);
                }
                fputcsv($out, []);
                fputcsv($out, ['TOTAL TABUNGAN', 'Rp ' . number_format($totalSaved, 0, ',', '.')]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Print-friendly HTML untuk diconvert ke PDF via browser (Ctrl+P → Save as PDF).
     * Lebih ringan & rendering lebih bagus dari library PHP.
     */
    public function pdf(Request $request)
    {
        [$year, $month, $entries, $savings] = $this->fetchData($request);

        $totalIn  = $entries->where('type', 'in')->sum('amount');
        $totalOut = $entries->where('type', 'out')->sum('amount');
        $balance  = $totalIn - $totalOut;

        $totalSaved = $savings->sum(fn($s) => (float) ($s->current_amount_raw ?? $s->current_amount));

        return view('finance.export-pdf', [
            'year'       => $year,
            'month'      => $month,
            'rangeLabel' => $month
                ? Carbon::create($year, $month)->translatedFormat('F Y')
                : 'Semua Waktu',
            'entries'    => $entries,
            'savings'    => $savings,
            'totalIn'    => $totalIn,
            'totalOut'   => $totalOut,
            'balance'    => $balance,
            'totalSaved' => $totalSaved,
            'generated'  => now(),
        ]);
    }

    /** Helper: ambil data + filter by month/year */
    private function fetchData(Request $request): array
    {
        $year  = (int) $request->query('year', 0);
        $month = (int) $request->query('month', 0);

        $query = FinanceEntry::query();
        if ($year && $month) {
            $query->inMonth($year, $month);
        }

        $entries = $query->orderBy('recorded_at')->orderBy('id')->get();

        $savings = Saving::query()
            ->where('is_active', true)
            ->withSum('deposits as current_amount_raw', 'amount')
            ->orderBy('name')
            ->get();

        return [$year, $month, $entries, $savings];
    }
}

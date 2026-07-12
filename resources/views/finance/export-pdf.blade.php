<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Finance Report · {{ $rangeLabel }}</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm;
        }

        :root {
            --soil-dark: #5C4632;
            --soil:      #83644A;
            --grass:     #4E7D4C;
            --berry:     #BE546E;
            --corn:      #C99845;
            --cream:     #FBF7EC;
            --border:    #E8DEC4;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            color: var(--soil-dark);
            background: white;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        /* ─── Print-only controls (hidden when printing) ─── */
        .print-bar {
            position: sticky;
            top: 0;
            background: var(--soil-dark);
            color: var(--cream);
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            z-index: 100;
        }
        .print-bar h2 { margin: 0; font-size: 14px; font-weight: 600; }
        .print-bar button {
            background: var(--corn);
            color: var(--soil-dark);
            border: none;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            border-radius: 4px;
        }
        .print-bar button:hover { background: #E5B567; }
        .print-bar a {
            color: var(--cream);
            text-decoration: none;
            font-size: 13px;
            margin-right: 12px;
            opacity: 0.85;
        }
        @media print {
            .print-bar { display: none; }
            body { padding: 0; }
        }

        /* ─── Document layout ─── */
        .doc {
            max-width: 800px;
            margin: 24px auto;
            padding: 32px;
            background: white;
            border: 1px solid var(--border);
            box-shadow: 0 1px 6px rgba(0,0,0,0.05);
        }
        @media print {
            .doc { margin: 0; padding: 0; border: none; box-shadow: none; max-width: none; }
        }

        header {
            border-bottom: 2px solid var(--soil-dark);
            padding-bottom: 14px;
            margin-bottom: 20px;
        }
        header h1 {
            margin: 0 0 4px 0;
            font-size: 22px;
            color: var(--soil-dark);
            letter-spacing: -0.02em;
        }
        header .subtitle {
            color: var(--soil);
            font-size: 13px;
            margin: 0;
        }
        header .meta {
            float: right;
            text-align: right;
            font-size: 11px;
            color: var(--soil);
            margin-top: -32px;
        }

        h2 {
            font-size: 15px;
            color: var(--soil-dark);
            border-bottom: 1px solid var(--border);
            padding-bottom: 6px;
            margin: 24px 0 12px 0;
        }

        /* ─── Summary cards ─── */
        .summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 20px;
        }
        .summary > div {
            border: 1px solid var(--border);
            padding: 10px 12px;
            border-radius: 4px;
            background: #FAFAF5;
        }
        .summary .label {
            font-size: 10px;
            color: var(--soil);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }
        .summary .value {
            font-size: 14px;
            font-weight: 700;
        }
        .v-in     { color: var(--grass); }
        .v-out    { color: var(--berry); }
        .v-bal    { color: var(--soil-dark); }
        .v-saved  { color: var(--corn); }
        .v-neg    { color: var(--berry); }

        /* ─── Tables ─── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 16px;
        }
        thead th {
            background: var(--soil-dark);
            color: var(--cream);
            text-align: left;
            padding: 8px 10px;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        tbody td {
            padding: 7px 10px;
            border-bottom: 1px solid var(--border);
        }
        tbody tr:nth-child(even) td { background: #FBFAF6; }
        .num { text-align: right; font-variant-numeric: tabular-nums; font-weight: 600; }
        .badge {
            display: inline-block;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .badge-in  { background: #E2F0E0; color: var(--grass); }
        .badge-out { background: #F5D9DF; color: var(--berry); }

        tfoot td {
            padding: 10px;
            font-weight: 700;
            border-top: 2px solid var(--soil-dark);
            background: #F5EFE0;
        }

        /* ─── Empty / Footer ─── */
        .empty {
            text-align: center;
            padding: 20px;
            color: var(--soil);
            font-style: italic;
            font-size: 12px;
        }
        footer {
            margin-top: 32px;
            padding-top: 12px;
            border-top: 1px solid var(--border);
            text-align: center;
            font-size: 10px;
            color: var(--soil);
        }
    </style>
</head>
<body>

<div class="print-bar">
    <h2>Finance Report · {{ $rangeLabel }}</h2>
    <div>
        <a href="javascript:history.back()">← Kembali</a>
        <button onclick="window.print()">Print / Save as PDF</button>
    </div>
</div>

<div class="doc">
    <header>
        <div class="meta">
            Generated: <strong>{{ $generated->format('d M Y, H:i') }}</strong>
        </div>
        <h1>Life-Sim Dashboard</h1>
        <p class="subtitle">Laporan Keuangan · <strong>{{ $rangeLabel }}</strong></p>
    </header>

    <div class="summary">
        <div>
            <div class="label">Income</div>
            <div class="value v-in">+Rp {{ number_format($totalIn, 0, ',', '.') }}</div>
        </div>
        <div>
            <div class="label">Expense</div>
            <div class="value v-out">-Rp {{ number_format($totalOut, 0, ',', '.') }}</div>
        </div>
        <div>
            <div class="label">Saldo Bersih</div>
            <div class="value {{ $balance >= 0 ? 'v-bal' : 'v-neg' }}">
                Rp {{ number_format(abs($balance), 0, ',', '.') }}
            </div>
        </div>
        <div>
            <div class="label">Tabungan</div>
            <div class="value v-saved">Rp {{ number_format($totalSaved, 0, ',', '.') }}</div>
        </div>
    </div>

    <h2>Transaksi ({{ $entries->count() }})</h2>

    @if($entries->isEmpty())
        <div class="empty">Belum ada transaksi pada periode ini.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width: 90px;">Tanggal</th>
                    <th style="width: 75px;">Tipe</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th class="num" style="width: 130px;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entries as $e)
                    <tr>
                        <td>{{ $e->recorded_at->format('d M Y') }}</td>
                        <td>
                            <span class="badge {{ $e->type === 'in' ? 'badge-in' : 'badge-out' }}">
                                {{ $e->type === 'in' ? 'Income' : 'Expense' }}
                            </span>
                        </td>
                        <td>{{ ucfirst($e->category) }}</td>
                        <td>{{ $e->description ?? '-' }}</td>
                        <td class="num {{ $e->type === 'in' ? 'v-in' : 'v-out' }}">
                            {{ $e->type === 'in' ? '+' : '-' }}Rp {{ number_format($e->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">Saldo Bersih</td>
                    <td class="num {{ $balance >= 0 ? 'v-bal' : 'v-neg' }}">
                        {{ $balance >= 0 ? '+' : '-' }}Rp {{ number_format(abs($balance), 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    @endif

    @if($savings->isNotEmpty())
        <h2>Tabungan ({{ $savings->count() }})</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th class="num" style="width: 120px;">Saldo</th>
                    <th class="num" style="width: 120px;">Target</th>
                    <th style="width: 70px;">Progress</th>
                    <th style="width: 90px;">Deadline</th>
                </tr>
            </thead>
            <tbody>
                @foreach($savings as $s)
                    @php
                        $current = (float) ($s->current_amount_raw ?? $s->current_amount);
                        $target  = (float) $s->target_amount;
                        $percent = $target > 0 ? round(($current / $target) * 100) : null;
                    @endphp
                    <tr>
                        <td>{{ $s->icon }} <strong>{{ $s->name }}</strong></td>
                        <td class="num v-saved">Rp {{ number_format($current, 0, ',', '.') }}</td>
                        <td class="num">{{ $target > 0 ? 'Rp ' . number_format($target, 0, ',', '.') : '-' }}</td>
                        <td>{{ $percent !== null ? $percent . '%' : '-' }}</td>
                        <td>{{ $s->target_date?->format('d M Y') ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>Total Tabungan</td>
                    <td class="num v-saved">Rp {{ number_format($totalSaved, 0, ',', '.') }}</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>
    @endif

    <footer>
        Life-Sim Dashboard · Personal Finance Report · {{ $generated->format('d/m/Y H:i') }}
    </footer>
</div>

<script>
    // Auto-show print dialog setelah load (optional, dikomentari kalau ganggu)
    // window.addEventListener('load', () => setTimeout(() => window.print(), 500));
</script>
</body>
</html>

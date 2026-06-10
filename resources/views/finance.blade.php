<x-app-layout>
    <x-slot name="title">Gold Ledger - Life-Sim Dashboard</x-slot>

    <section class="page-hero">
        <div class="page-hero-content">
            <div>
                <a href="{{ route('dashboard') }}" class="page-kicker no-underline">
                    <span>Back to Dashboard</span>
                </a>
                <h1 class="page-title">Gold Ledger</h1>
                <p class="page-description">
                    Pusat kontrol keuangan personal: transaksi harian, ringkasan pengeluaran,
                    export Excel/PDF, chart kategori, dan target tabungan bulanan.
                </p>
            </div>
            <div class="page-actions">
                <a href="{{ route('quests.index') }}" class="btn-ghost">Open Quests</a>
                <a href="{{ route('library.index') }}" class="btn-ghost">Open Library</a>
            </div>
        </div>
    </section>

    @php
        $incomeTotal = \App\Models\FinanceEntry::where('type', 'in')->sum('amount');
        $expenseTotal = \App\Models\FinanceEntry::where('type', 'out')->sum('amount');
        $netTotal = $incomeTotal - $expenseTotal;
        $savingsTotal = \App\Models\SavingDeposit::sum('amount');
    @endphp

    <section class="metric-strip">
        <div class="metric-tile">
            <p class="metric-label">Total Income</p>
            <p class="metric-value text-grass-dark">Rp {{ number_format($incomeTotal, 0, ',', '.') }}</p>
        </div>
        <div class="metric-tile">
            <p class="metric-label">Total Expense</p>
            <p class="metric-value text-berry-dark">Rp {{ number_format($expenseTotal, 0, ',', '.') }}</p>
        </div>
        <div class="metric-tile">
            <p class="metric-label">Net Balance</p>
            <p class="metric-value {{ $netTotal >= 0 ? 'text-grass-dark' : 'text-berry-dark' }}">Rp {{ number_format($netTotal, 0, ',', '.') }}</p>
        </div>
        <div class="metric-tile">
            <p class="metric-label">Savings</p>
            <p class="metric-value text-corn-dark">Rp {{ number_format($savingsTotal, 0, ',', '.') }}</p>
        </div>
    </section>

    <div x-data="financeExport()" class="panel p-0 mb-5 overflow-hidden">
        <div class="form-shell-header">
            <div>
                <p class="form-shell-title">
                    <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-sky-dark">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Export & Reconcile
                </p>
                <p class="form-shell-subtitle">Download transaksi untuk dicocokkan dengan rekening, e-wallet, atau catatan manual.</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <select x-model="scope" class="input-pixel py-2 px-3" style="width: auto; font-size: 12px;">
                    <option value="month">Bulan ini ({{ now()->format('M Y') }})</option>
                    <option value="all">Semua waktu</option>
                </select>
                <a :href="csvUrl()" target="_blank" class="btn-ghost">CSV / Excel</a>
                <a :href="pdfUrl()" target="_blank" class="btn-primary">PDF / Print</a>
            </div>
        </div>
    </div>

    <script>
    function financeExport() {
        return {
            scope: 'month',
            csvUrl() {
                if (this.scope === 'month') {
                    return `{{ route('finance.export.csv') }}?year={{ now()->year }}&month={{ now()->month }}`;
                }
                return `{{ route('finance.export.csv') }}`;
            },
            pdfUrl() {
                if (this.scope === 'month') {
                    return `{{ route('finance.export.pdf') }}?year={{ now()->year }}&month={{ now()->month }}`;
                }
                return `{{ route('finance.export.pdf') }}`;
            }
        }
    }
    </script>

    <div class="mb-5">
        @livewire('finance-charts')
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
        <div>@livewire('gold-ledger')</div>
        <div>@livewire('savings-tracker')</div>
    </div>
</x-app-layout>

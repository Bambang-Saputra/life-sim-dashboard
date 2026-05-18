<x-app-layout>
    <x-slot name="title">Gold Ledger · Life-Sim Dashboard</x-slot>

    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('dashboard') }}" class="font-sans text-xs text-soil hover:text-grass-dark no-underline">← Dashboard</a>
        </div>
        <h1 class="font-pixel text-soil-dark flex items-center gap-3" style="font-size: 14px; letter-spacing: 0.05em;">
            <svg viewBox="0 0 24 24" fill="none" class="w-6 h-6 text-corn-dark">
                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                <path d="M12 6v12M9 9h4.5a2.5 2.5 0 0 1 0 5h-3a2.5 2.5 0 0 0 0 5H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            GOLD LEDGER
        </h1>
        <p class="font-sans text-soil text-sm mt-2">Catatan transaksi, chart bulanan, dan tabungan terpisah.</p>
    </div>

    {{-- ── EXPORT TOOLBAR ── --}}
    <div x-data="financeExport()" class="mb-5 panel p-4">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-wrap">

            <div class="flex-1 min-w-0">
                <p class="font-sans font-semibold text-soil-dark text-sm flex items-center gap-2">
                    <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-sky-dark">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Export Data Keuangan
                </p>
                <p class="font-sans text-stone text-xs mt-1">Download untuk dicek & disesuaikan dengan keuangan riil.</p>
            </div>

            {{-- Period selector --}}
            <div class="flex items-center gap-2">
                <label class="font-sans text-soil text-xs">Periode:</label>
                <select x-model="scope" class="input-pixel py-1.5 px-2.5" style="width: auto; font-size: 12px;">
                    <option value="month">Bulan ini ({{ now()->format('M Y') }})</option>
                    <option value="all">Semua waktu</option>
                </select>
            </div>

            {{-- Export buttons --}}
            <div class="flex items-center gap-2">
                <a :href="csvUrl()" target="_blank"
                   class="btn-ghost flex items-center gap-1.5 py-2 px-3">
                    <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-grass-dark">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z M14 2v6h6 M8 13h2 M8 17h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    CSV / Excel
                </a>

                <a :href="pdfUrl()" target="_blank"
                   class="btn-primary flex items-center gap-1.5 py-2 px-3">
                    <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4">
                        <path d="M6 9V2h12v7 M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2 M6 14h12v8H6z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    PDF / Print
                </a>
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

    {{-- Charts (top) --}}
    <div class="mb-5">
        @livewire('finance-charts')
    </div>

    {{-- Ledger + Savings (2-col) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <div>@livewire('gold-ledger')</div>
        <div>@livewire('savings-tracker')</div>
    </div>

</x-app-layout>

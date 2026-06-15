<x-app-layout>
    <x-slot name="title">Gold Ledger - Life-Sim Dashboard</x-slot>

    <section class="page-hero">
        <div class="page-hero-content">
            <div class="page-hero-text">
                <a href="{{ route('dashboard') }}" class="page-kicker no-underline">
                    <span>Back to Dashboard</span>
                </a>
                <h1 class="page-title">Gold Ledger</h1>
                <p class="page-description">
                    Pusat kontrol keuangan personal: transaksi harian, ringkasan pengeluaran,
                    export Excel/PDF, chart kategori, dan target tabungan bulanan.
                </p>
                <div class="page-actions mt-4">
                    <a href="{{ route('quests.index') }}" class="btn-ghost">Open Quests</a>
                    <a href="{{ route('library.index') }}" class="btn-ghost">Open Library</a>
                </div>
            </div>

            {{-- 💰 Pixel scene: ledger book + gold --}}
            <div class="page-hero-art" aria-hidden="true">
                {{-- warm glow --}}
                <div class="anim-shimmer" style="position:absolute; top:24px; left:150px; width:96px; height:96px; background:radial-gradient(circle,rgba(229,181,103,0.5),transparent 68%);"></div>

                {{-- desk line --}}
                <div style="position:absolute; bottom:24px; left:18px; right:14px; height:4px; background:#C99845; opacity:.45; border-radius:2px;"></div>

                {{-- floating coins with Rp --}}
                <div class="anim-sway" style="position:absolute; top:14px; left:120px; width:22px; height:22px; border-radius:50%; background:radial-gradient(circle at 35% 30%,#F1CC8E,#C99845); border:2px solid #A8761F; display:flex; align-items:center; justify-content:center;">
                    <span class="font-pixel" style="font-size:7px; color:#7A5512;">Rp</span>
                </div>
                <div class="anim-sway" style="position:absolute; top:6px; left:196px; width:16px; height:16px; border-radius:50%; background:radial-gradient(circle at 35% 30%,#F1CC8E,#C99845); border:2px solid #A8761F; animation-delay:.6s;"></div>

                {{-- up-trend arrow --}}
                <div style="position:absolute; top:30px; left:228px;">
                    <div style="width:0;height:0;border-left:6px solid transparent;border-right:6px solid transparent;border-bottom:9px solid #6BA368;margin:0 auto;"></div>
                    <div style="width:4px;height:14px;background:#6BA368;margin:0 auto;"></div>
                </div>

                {{-- OPEN LEDGER BOOK --}}
                <div style="position:absolute; bottom:26px; left:30px;">
                    {{-- spine shadow --}}
                    <div style="position:absolute; bottom:-4px; left:-4px; width:150px; height:10px; background:#5C4632; opacity:.25; border-radius:50%;"></div>
                    {{-- left page --}}
                    <div style="position:absolute; bottom:0; left:0; width:70px; height:54px; background:#FBF7EC; border:2px solid #C9A86A; transform:perspective(120px) rotateY(16deg); transform-origin:right;">
                        <div style="margin:8px 7px;">
                            <div style="height:2px;background:#D9CBA6;margin-bottom:5px;"></div>
                            <div style="height:2px;background:#D9CBA6;margin-bottom:5px;width:80%;"></div>
                            <div style="height:2px;background:#D9CBA6;margin-bottom:5px;"></div>
                            <div style="height:2px;background:#9DBE8C;margin-bottom:5px;width:60%;"></div>
                        </div>
                    </div>
                    {{-- right page --}}
                    <div style="position:absolute; bottom:0; left:68px; width:70px; height:54px; background:#FBF7EC; border:2px solid #C9A86A; transform:perspective(120px) rotateY(-16deg); transform-origin:left;">
                        <div style="margin:8px 7px;">
                            <div style="height:2px;background:#D9CBA6;margin-bottom:5px;"></div>
                            <div style="height:2px;background:#C98AA0;margin-bottom:5px;width:70%;"></div>
                            <div style="height:2px;background:#D9CBA6;margin-bottom:5px;"></div>
                            <div style="height:2px;background:#9DBE8C;margin-bottom:5px;width:85%;"></div>
                        </div>
                    </div>
                    {{-- center binding --}}
                    <div style="position:absolute; bottom:0; left:66px; width:6px; height:54px; background:#9A3F56; border:1px solid #5C4632;"></div>
                </div>

                {{-- gold coin stacks --}}
                <div style="position:absolute; bottom:26px; left:182px;">
                    @for($c=0;$c<5;$c++)
                        <div style="width:26px;height:6px;border-radius:50%;background:linear-gradient(180deg,#F1CC8E,#D4A95C);border:1px solid #A8761F;margin-top:-1px;"></div>
                    @endfor
                </div>
                <div style="position:absolute; bottom:26px; left:214px;">
                    @for($c=0;$c<3;$c++)
                        <div style="width:24px;height:6px;border-radius:50%;background:linear-gradient(180deg,#F1CC8E,#D4A95C);border:1px solid #A8761F;margin-top:-1px;"></div>
                    @endfor
                </div>
                {{-- single coin --}}
                <div style="position:absolute; bottom:26px; left:248px; width:18px; height:18px; border-radius:50%; background:radial-gradient(circle at 35% 30%,#F1CC8E,#C99845); border:2px solid #A8761F;"></div>
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

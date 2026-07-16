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
            <div class="page-hero-art is-live" aria-hidden="false">
                <div class="pxs"
                     x-data="pixelScene"
                     :class="burst && 'is-burst'"
                     @mousemove="onMove($event)"
                     @mouseleave="resetPointer"
                     :style="`--px:${px};--py:${py}`">
                    {{-- dinding: 2 pita + dither --}}
                    <div class="absolute inset-x-0" style="top:0; height:60px; background:#EED9BB;"></div>
                    <div class="absolute inset-x-0" style="top:60px; height:38px; background:#E4CBA6;"></div>
                    <div class="px-dither" style="top:56px; --da:#E4CBA6; --db:#EED9BB;"></div>
                    {{-- wainscot + lantai papan --}}
                    <div class="absolute inset-x-0" style="top:98px; height:4px; background:#5C4632;"></div>
                    <div class="absolute inset-x-0" style="top:102px; height:12px; background:#83644A;"></div>
                    <div class="absolute inset-x-0" style="top:114px; height:2px; background:#5C4632;"></div>
                    <div class="absolute inset-x-0" style="top:116px; height:40px; background:#B8804A; background-image:repeating-linear-gradient(90deg, transparent 0 46px, #9A6A3D 46px 49px);"></div>

                    <div class="par par-far">
                        {{-- jendela + matahari --}}
                        <div class="absolute" style="right:16px; top:10px; width:76px; height:58px; background:#5C4632;">
                            <span class="absolute" style="inset:5px; background:#8CB8DE;"></span>
                            <span class="absolute" style="left:5px; right:5px; bottom:5px; height:14px; background:#A9CBE7;"></span>
                            <span class="absolute anim-shimmer" style="right:14px; top:12px; width:12px; height:12px; background:#F1CC8E; box-shadow:0 0 10px rgba(249,217,75,0.8);"></span>
                            <span class="absolute" style="left:50%; top:5px; bottom:5px; width:4px; margin-left:-2px; background:#5C4632;"></span>
                            <span class="absolute" style="left:5px; right:5px; top:50%; height:4px; margin-top:-2px; background:#5C4632;"></span>
                        </div>
                        {{-- debu melayang di berkas cahaya jendela --}}
                        <span class="px-mote" style="right:36px; top:34px;"></span>
                        <span class="px-mote" style="right:56px; top:48px; animation-delay:2s;"></span>
                        <span class="px-mote" style="right:70px; top:26px; animation-delay:4s;"></span>
                        {{-- lukisan mini chart --}}
                        <div class="absolute" style="left:18px; top:16px; width:44px; height:34px; background:#5C4632;">
                            <span class="absolute" style="inset:4px; background:#FBF7EC;"></span>
                            <span class="absolute" style="left:8px; bottom:6px; width:6px; height:10px; background:#BE546E;"></span>
                            <span class="absolute" style="left:18px; bottom:6px; width:6px; height:15px; background:#E5B567;"></span>
                            <span class="absolute" style="left:28px; bottom:6px; width:6px; height:20px; background:#6BA368;"></span>
                        </div>
                    </div>

                    <div class="par par-mid">
                        {{-- brankas interaktif: klik = goyang + koin --}}
                        <button type="button" @click="pop" class="fx-vault"
                                :class="burst && 'is-shake'"
                                style="right:26px; top:62px; width:64px; height:60px; background:#A9A39E; box-shadow: inset 0 4px 0 #C9C4C0, inset 4px 0 0 #C9C4C0, inset 0 -4px 0 #7B7672;"
                                title="Brankas tabungan (klik!)"
                                aria-label="Brankas, klik untuk animasi koin">
                            <span class="absolute" style="inset:8px 10px; background:#8D8781;"></span>
                            <span class="fx-dial"></span>
                            <span class="absolute" style="right:7px; top:22px; width:5px; height:18px; background:#5C4632;"></span>
                        </button>
                        <div class="fx-burst-coin" style="right:50px; top:48px;"></div>
                    </div>

                    <div class="par par-near">
                        {{-- meja + buku besar + kertas --}}
                        <div class="absolute" style="left:16px; top:92px; width:104px; height:8px; background:#8A5B33; box-shadow: 0 3px 0 #6E4728;"></div>
                        <div class="absolute" style="left:22px; top:103px; width:9px; height:26px; background:#5C4632;"></div>
                        <div class="absolute" style="left:104px; top:103px; width:9px; height:26px; background:#5C4632;"></div>
                        <div class="absolute" style="left:28px; top:78px; width:38px; height:6px; background:#FBF7EC;"></div>
                        <div class="absolute" style="left:28px; top:84px; width:38px; height:8px; background:#9A3F56;"></div>
                        <div class="absolute" style="left:74px; top:84px; width:26px; height:4px; background:#FBF7EC;"></div>
                        <div class="absolute" style="left:78px; top:88px; width:26px; height:4px; background:#E8DEC4;"></div>
                        {{-- koin glint --}}
                        <div class="fx-coin" style="left:102px; top:78px;"></div>
                        <div class="fx-coin" style="left:102px; top:84px; animation-delay:.8s;"></div>
                        {{-- celengan (fitur savings) --}}
                        <div class="fx-piggy absolute" style="left:150px; top:96px; width:48px; height:26px;" title="Savings goals">
                            <span class="absolute" style="inset:0 6px; background:#D4869A; box-shadow: inset 0 4px 0 #E3A7B5;"></span>
                            <span class="absolute" style="left:10px; top:-5px; width:5px; height:5px; background:#D4869A;"></span>
                            <span class="absolute" style="right:8px; top:-5px; width:5px; height:5px; background:#D4869A;"></span>
                            <span class="absolute" style="left:50%; top:-4px; width:10px; height:4px; margin-left:-5px; background:#5C4632;"></span>
                            <span class="absolute" style="right:-2px; top:8px; width:10px; height:10px; background:#BE546E;"></span>
                            <span class="absolute" style="right:0; top:11px; width:3px; height:3px; background:#9A3F56;"></span>
                            <span class="absolute" style="right:14px; top:7px; width:4px; height:4px; background:#5C4632;"></span>
                            <span class="absolute" style="left:12px; bottom:-5px; width:5px; height:5px; background:#BE546E;"></span>
                            <span class="absolute" style="right:12px; bottom:-5px; width:5px; height:5px; background:#BE546E;"></span>
                        </div>
                    </div>
                </div>
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
            @php
                $exportMonths = \App\Models\FinanceEntry::orderByDesc('recorded_at')
                    ->pluck('recorded_at')
                    ->map(fn ($d) => $d->format('Y-m'))
                    ->unique()->values();
                $exportRange = $exportMonths->isEmpty() ? '' :
                    ' (' . \Carbon\Carbon::createFromFormat('Y-m', $exportMonths->last())->translatedFormat('M Y')
                    . ' s.d. ' . \Carbon\Carbon::createFromFormat('Y-m', $exportMonths->first())->translatedFormat('M Y') . ')';
                $defaultScope = $exportMonths->contains(now()->format('Y-m')) ? now()->format('Y-m') : 'all';
            @endphp
            <div class="flex items-center gap-2 flex-wrap">
                <select x-model="scope" class="input-pixel py-2 px-3" style="width: auto; font-size: 12px;">
                    @foreach($exportMonths as $ym)
                        <option value="{{ $ym }}">
                            {{ \Carbon\Carbon::createFromFormat('Y-m', $ym)->translatedFormat('F Y') }}{{ $ym === now()->format('Y-m') ? ' (bulan ini)' : '' }}
                        </option>
                    @endforeach
                    <option value="all">Semua waktu{{ $exportRange }}</option>
                </select>
                <a :href="csvUrl()" target="_blank" class="btn-ghost">CSV / Excel</a>
                <a :href="pdfUrl()" target="_blank" class="btn-primary">PDF / Print</a>
            </div>
        </div>
    </div>

    <script>
    function financeExport() {
        return {
            scope: '{{ $defaultScope }}',
            withScope(base) {
                if (this.scope === 'all') return base;
                const [year, month] = this.scope.split('-');
                return `${base}?year=${year}&month=${parseInt(month, 10)}`;
            },
            csvUrl() { return this.withScope(`{{ route('finance.export.csv') }}`); },
            pdfUrl() { return this.withScope(`{{ route('finance.export.pdf') }}`); }
        }
    }
    </script>

    {{-- Insight otomatis: bulan ini vs bulan lalu, proyeksi, status budget --}}
    @livewire('finance-insights')

    <div class="mb-5">
        @livewire('finance-charts')
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-5">
        <div>@livewire('gold-ledger')</div>
        <div class="space-y-5">
            @livewire('budget-board')
            @livewire('recurring-manager')
            @livewire('savings-tracker')
        </div>
    </div>
</x-app-layout>

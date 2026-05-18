<div class="panel">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
        <h2 class="section-title">
            <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-sky-dark">
                <path d="M3 3v18h18M7 12l4-4 4 4 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            ANALYTICS
        </h2>

        <div class="flex items-center gap-2">
            <button type="button" wire:click="prevMonth" class="btn-icon">
                <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </button>
            <span class="font-sans font-semibold text-soil-dark text-sm min-w-[110px] text-center">
                {{ \Carbon\Carbon::create($viewYear, $viewMonth)->format('F Y') }}
            </span>
            <button type="button" wire:click="nextMonth" class="btn-icon">
                <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4"><path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Daily bar chart (income vs expense) --}}
        @php $daily = $this->dailyData; @endphp
        <div class="lg:col-span-2">
            <p class="font-sans font-semibold text-soil text-xs uppercase tracking-wider mb-2">Income vs Expense Harian</p>
            <div class="bg-cream/30 border border-cream-dark p-3" style="border-radius: 6px;">
                <div
                    wire:ignore
                    wire:key="bar-chart-{{ $viewYear }}-{{ $viewMonth }}"
                    x-data='barChart({
                        data: {
                            labels: @json($daily["days"]),
                            datasets: [
                                { label: "Income",  data: @json($daily["income"]),  backgroundColor: "#6BA368" },
                                { label: "Expense", data: @json($daily["expense"]), backgroundColor: "#BE546E" }
                            ]
                        }
                    })'
                    style="height: 240px;"
                >
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>
        </div>

        {{-- Expense pie chart with range toggle --}}
        @php
            $pie = $this->expensePie;
            $pieLabels = array_map(fn($r) => $r['category'] . ' (' . $r['percent'] . '%)', $pie['rows']);
            $pieTotals = array_column($pie['rows'], 'total');
            $palette = ['#BE546E','#E5B567','#6BA368','#77AADD','#83644A','#A88B6E','#9A3F56','#C99845','#4E7D4C','#D4869A'];
        @endphp
        <div>
            <div class="flex items-center justify-between mb-2 gap-2 flex-wrap">
                <p class="font-sans font-semibold text-soil text-xs uppercase tracking-wider">
                    Pengeluaran per Kategori
                </p>
                {{-- Range toggle --}}
                <div class="flex gap-1">
                    @foreach(['day' => 'Hari', 'month' => 'Bulan', 'year' => 'Tahun'] as $r => $label)
                        <button type="button" wire:click="$set('pieRange', '{{ $r }}')"
                            class="filter-btn px-2.5 {{ $pieRange === $r ? 'is-active' : '' }}"
                            style="font-size: 9px;">{{ $label }}</button>
                    @endforeach
                </div>
            </div>

            <div class="bg-cream/30 border border-cream-dark p-3" style="border-radius: 6px; min-height: 240px;">
                @if(empty($pie['rows']))
                    <div class="flex flex-col items-center justify-center h-full text-stone text-sm py-12">
                        <p class="font-sans">Belum ada expense untuk periode <strong>{{ $pie['label'] }}</strong></p>
                    </div>
                @else
                    {{-- Pie label header --}}
                    <p class="font-sans text-xs text-soil text-center mb-2">
                        {{ $pie['label'] }}
                        <span class="text-stone">·</span>
                        Total <span class="font-semibold text-berry">Rp {{ number_format($pie['grand_total'], 0, ',', '.') }}</span>
                    </p>

                    <div
                        wire:ignore
                        wire:key="pie-{{ $pieRange }}-{{ $viewYear }}-{{ $viewMonth }}"
                        x-data='doughnutChart({
                            data: {
                                labels: @json($pieLabels),
                                datasets: [{
                                    data: @json($pieTotals),
                                    backgroundColor: @json(array_slice($palette, 0, count($pie["rows"]))),
                                    borderColor: "#FBF7EC",
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                plugins: {
                                    legend: {
                                        position: "bottom",
                                        labels: { font: { family: "Inter", size: 10 }, color: "#5C4632", padding: 8, boxWidth: 12 }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: (ctx) => {
                                                const val = ctx.parsed;
                                                return " Rp " + new Intl.NumberFormat("id-ID").format(val);
                                            }
                                        }
                                    }
                                }
                            }
                        })'
                        style="height: 200px;"
                    >
                        <canvas x-ref="canvas"></canvas>
                    </div>
                @endif
            </div>
        </div>

        {{-- Monthly trend (6 months) --}}
        @php $trend = $this->monthlyTrend; @endphp
        <div class="lg:col-span-3">
            <p class="font-sans font-semibold text-soil text-xs uppercase tracking-wider mb-2">Net Balance Trend (6 Bulan)</p>
            <div class="bg-cream/30 border border-cream-dark p-3" style="border-radius: 6px;">
                <div
                    wire:ignore
                    wire:key="line-trend"
                    x-data='lineChart({
                        data: {
                            labels: @json($trend["months"]),
                            datasets: [{
                                label: "Net Balance",
                                data: @json($trend["net"]),
                                borderColor: "#4E7D4C",
                                backgroundColor: "rgba(107,163,104,0.18)",
                                fill: true
                            }]
                        }
                    })'
                    style="height: 180px;"
                >
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>

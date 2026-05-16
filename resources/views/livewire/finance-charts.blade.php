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

        {{-- Category doughnut --}}
        @php
            $cats = $this->categoryBreakdown;
            $catLabels = array_column($cats, 'category');
            $catTotals = array_column($cats, 'total');
            $palette = ['#BE546E','#E5B567','#6BA368','#77AADD','#83644A','#A88B6E','#9A3F56','#C99845'];
        @endphp
        <div>
            <p class="font-sans font-semibold text-soil text-xs uppercase tracking-wider mb-2">Top Expense Categories</p>
            <div class="bg-cream/30 border border-cream-dark p-3" style="border-radius: 6px; min-height: 240px;">
                @if(empty($cats))
                    <div class="flex flex-col items-center justify-center h-full text-stone text-sm py-12">
                        <p class="font-sans">Belum ada expense bulan ini</p>
                    </div>
                @else
                    <div
                        wire:ignore
                        wire:key="doughnut-{{ $viewYear }}-{{ $viewMonth }}"
                        x-data='doughnutChart({
                            data: {
                                labels: @json($catLabels),
                                datasets: [{
                                    data: @json($catTotals),
                                    backgroundColor: @json(array_slice($palette, 0, count($cats))),
                                    borderColor: "#FBF7EC",
                                    borderWidth: 2
                                }]
                            }
                        })'
                        style="height: 220px;"
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

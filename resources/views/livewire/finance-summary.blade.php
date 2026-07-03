<div class="panel h-full flex flex-col">
    <div class="flex items-center justify-between mb-4">
        <h2 class="section-title">
            <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-corn-dark">
                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                <path d="M12 6v12M9 9h4.5a2.5 2.5 0 0 1 0 5h-3a2.5 2.5 0 0 0 0 5H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            GOLD LEDGER
        </h2>
        <a href="{{ route('finance.index') }}" class="font-sans text-xs text-grass-dark hover:text-grass font-semibold no-underline">View Detail →</a>
    </div>

    {{-- Chip status budget bulan ini (klik menuju Budget Board) --}}
    @if($this->budgetAlert)
        <a href="{{ route('finance.index') }}"
           class="insight-card insight-{{ $this->budgetAlert['tone'] }} block mb-4 no-underline hover:brightness-105 transition">
            <p class="font-sans text-sm text-soil-dark flex items-center gap-2">
                <span>{{ ['danger' => '🚨', 'warning' => '⏳', 'success' => '🛡️'][$this->budgetAlert['tone']] }}</span>
                <span class="font-semibold">{{ $this->budgetAlert['text'] }}</span>
            </p>
        </a>
    @endif

    {{-- Balance + Savings --}}
    <div class="grid grid-cols-2 gap-2 mb-4">
        <div class="px-3 py-3 border border-cream-dark bg-cream/40" style="border-radius: 6px;">
            <p class="font-sans text-soil text-xs uppercase tracking-wider">Net Balance</p>
            <p class="font-pixel {{ $this->netBalance >= 0 ? 'text-grass-dark' : 'text-berry' }} mt-1" style="font-size: 11px;">
                {{ $this->netBalance >= 0 ? '+' : '-' }}Rp {{ number_format(abs($this->netBalance), 0, ',', '.') }}
            </p>
        </div>
        <div class="px-3 py-3 border border-corn/30 bg-corn-light/30" style="border-radius: 6px;">
            <p class="font-sans text-soil text-xs uppercase tracking-wider">Tabungan</p>
            <p class="font-pixel text-corn-dark mt-1" style="font-size: 11px;">
                Rp {{ number_format($this->totalSavings, 0, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- 7-day chart --}}
    @php $chart = $this->chartData; @endphp
    <div class="mb-4">
        <p class="font-sans font-semibold text-soil text-xs uppercase tracking-wider mb-2">7 Hari Terakhir</p>
        <div
            x-data='lineChart({
                data: {
                    labels: @json($chart["days"]),
                    datasets: [
                        { label: "Income",  data: @json($chart["income"]),  borderColor: "#4E7D4C", backgroundColor: "rgba(107,163,104,0.18)", fill: true },
                        { label: "Expense", data: @json($chart["expense"]), borderColor: "#BE546E", backgroundColor: "rgba(190,84,110,0.15)",  fill: true },
                    ]
                }
            })'
            style="height: 140px;"
        >
            <canvas x-ref="canvas"></canvas>
        </div>
    </div>

    {{-- Recent entries --}}
    <div class="space-y-1.5 flex-1">
        <p class="font-sans font-semibold text-soil text-xs uppercase tracking-wider mb-1">Transaksi Terbaru</p>

        @forelse($this->recentEntries as $entry)
            <div class="card-item flex items-center gap-2 px-3 py-2
                {{ $entry->type === 'in' ? 'border-l-2 !border-l-grass' : 'border-l-2 !border-l-berry/60' }}">
                <span class="font-pixel flex-shrink-0 {{ $entry->type === 'in' ? 'text-grass-dark' : 'text-berry' }}" style="font-size: 9px;">
                    {{ $entry->type === 'in' ? '▲' : '▼' }}
                </span>
                <div class="flex-1 min-w-0">
                    <p class="font-sans font-medium text-soil-dark text-sm truncate">{{ ucfirst($entry->category) }}</p>
                    <p class="font-sans text-stone text-xs">{{ $entry->recorded_at->format('d M') }}</p>
                </div>
                <span class="font-mono font-semibold text-sm {{ $entry->type === 'in' ? 'text-grass-dark' : 'text-berry-dark' }}">
                    {{ $entry->type === 'in' ? '+' : '-' }}{{ number_format($entry->amount, 0, ',', '.') }}
                </span>
            </div>
        @empty
            <div class="empty-state py-6">
                <p class="font-sans text-stone text-sm">Belum ada transaksi.</p>
            </div>
        @endforelse
    </div>
</div>

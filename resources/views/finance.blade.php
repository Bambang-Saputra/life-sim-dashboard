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

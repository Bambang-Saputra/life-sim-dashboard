<div
    x-data="{ showForm: @entangle('showForm') }"
    @finance-entry-saved.window="showForm = false"
    class="panel flex flex-col"
>

    {{-- ── HEADER ── --}}
    <div class="flex items-start justify-between mb-5 gap-4 flex-wrap">
        <div>
            <h2 class="section-title mb-2">
                <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-corn-dark">
                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                    <path d="M12 6v12M9 9h4.5a2.5 2.5 0 0 1 0 5h-3a2.5 2.5 0 0 0 0 5H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                LEDGER
            </h2>
            <div class="flex items-baseline gap-2">
                <span class="font-sans text-soil text-xs">Net</span>
                <span class="font-pixel {{ $this->netBalance >= 0 ? 'text-grass-dark' : 'text-berry' }}" style="font-size: 12px;">
                    {{ $this->netBalance >= 0 ? '+' : '-' }}Rp {{ number_format(abs($this->netBalance), 0, ',', '.') }}
                </span>
            </div>
        </div>
        <button type="button" @click="showForm = !showForm" class="btn-primary flex-shrink-0">
            <span x-show="!showForm">+ New Entry</span>
            <span x-show="showForm">✕ Cancel</span>
        </button>
    </div>

    {{-- ── MONTHLY STATS ── --}}
    <div class="grid grid-cols-3 gap-2 mb-5">
        <div class="px-3 py-3 bg-grass-light/20 border border-grass/25" style="border-radius: 6px;">
            <p class="font-sans text-soil text-xs uppercase tracking-wider" style="font-size: 10px;">Income</p>
            <p class="font-pixel text-grass-dark mt-1.5" style="font-size: 10px;">+{{ number_format($this->summary['income'], 0, ',', '.') }}</p>
        </div>
        <div class="px-3 py-3 bg-berry-light/20 border border-berry/25" style="border-radius: 6px;">
            <p class="font-sans text-soil text-xs uppercase tracking-wider" style="font-size: 10px;">Expense</p>
            <p class="font-pixel text-berry mt-1.5" style="font-size: 10px;">-{{ number_format($this->summary['expense'], 0, ',', '.') }}</p>
        </div>
        <div class="px-3 py-3 bg-cream border border-cream-dark" style="border-radius: 6px;">
            <p class="font-sans text-soil text-xs uppercase tracking-wider" style="font-size: 10px;">Saldo</p>
            <p class="font-pixel {{ $this->summary['balance'] >= 0 ? 'text-grass-dark' : 'text-berry' }} mt-1.5" style="font-size: 10px;">
                {{ number_format($this->summary['balance'], 0, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- ── FORM ── --}}
    <div x-show="showForm" x-collapse>
        <form wire:submit.prevent="save"
              class="form-shell">
            <div class="form-shell-header">
                <div>
                    <p class="form-shell-title">
                        <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-corn-dark">
                            <path d="M12 6v12M7 10h7a3 3 0 0 1 0 6H8m8 2H7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        {{ $editingId ? 'Edit Transaction' : 'Add Transaction' }}
                    </p>
                    <p class="form-shell-subtitle">
                        Catat pemasukan atau pengeluaran dengan kategori yang sesuai agar chart tetap akurat.
                    </p>
                </div>
                <span class="{{ $type === 'in' ? 'tag-grass' : 'tag-berry' }}">
                    {{ $type === 'in' ? 'Income' : 'Expense' }}
                </span>
            </div>
            <p class="hidden" style="font-size: 9px;">
                {{ $editingId ? '✏ EDIT ENTRY' : '✚ NEW ENTRY' }}
            </p>

            <div class="form-shell-body grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="field-label">Type *</label>
                    <select wire:model.live="type" class="input-pixel">
                        <option value="in">↑ Income (Gold In)</option>
                        <option value="out">↓ Expense (Gold Out)</option>
                    </select>
                    @error('type') <p class="font-sans text-berry text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="field-label">Amount (Rp) *</label>
                    <input wire:model="amount" type="number" min="0" step="1000" placeholder="50000" class="input-pixel">
                    @error('amount') <p class="font-sans text-berry text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="field-label">
                        Category *
                        <span class="text-stone font-normal normal-case">
                            ({{ $type === 'in' ? '💰 income' : '💸 expense' }})
                        </span>
                    </label>
                    <input wire:model="category" type="text"
                        placeholder="{{ $type === 'in' ? 'Salary, Bonus, Freelance...' : 'Food, Transport, Shopping...' }}"
                        class="input-pixel" list="cat-list">

                    {{-- Datalist diganti sesuai type aktif --}}
                    @if($type === 'in')
                        <datalist id="cat-list">
                            <option value="Salary">
                            <option value="Bonus">
                            <option value="Freelance">
                            <option value="Investment">
                            <option value="Side Hustle">
                            <option value="Gift">
                            <option value="Refund">
                            <option value="Other Income">
                        </datalist>
                    @else
                        <datalist id="cat-list">
                            <option value="Food">
                            <option value="Transport">
                            <option value="Shopping">
                            <option value="Entertainment">
                            <option value="Utilities">
                            <option value="Health">
                            <option value="Subscription">
                            <option value="Rent">
                            <option value="Education">
                            <option value="Other Expense">
                        </datalist>
                    @endif
                    @error('category') <p class="font-sans text-berry text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="field-label">Date *</label>
                    <input wire:model="recorded_at" type="date" class="input-pixel">
                    @error('recorded_at') <p class="font-sans text-berry text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="field-label">Description</label>
                    <input wire:model="description" type="text" placeholder="Catatan tambahan..." class="input-pixel">
                </div>

                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" wire:loading.attr="disabled" wire:target="save" class="btn-primary">
                        <span wire:loading.remove wire:target="save">{{ $editingId ? '💾 Update' : '✚ Add Entry' }}</span>
                        <span wire:loading wire:target="save" class="animate-blink">Saving...</span>
                    </button>
                    <button type="button" @click="showForm = false" class="btn-ghost">Cancel</button>
                </div>
            </div>
        </form>
    </div>

    {{-- ── SEARCH + RENTANG TANGGAL ── --}}
    <div class="mb-3 p-3 bg-cream/40 border border-cream-dark" style="border-radius: 6px;">
        <div class="flex flex-wrap items-end gap-2">
            <div class="flex-1 min-w-[140px]">
                <label class="field-label">Cari</label>
                <input wire:model.live.debounce.400ms="search" type="search"
                       placeholder="kategori atau deskripsi..." class="input-pixel">
            </div>
            <div>
                <label class="field-label">Dari</label>
                <input wire:model.live="dateFrom" type="date" class="input-pixel" style="width: 140px;">
            </div>
            <div>
                <label class="field-label">Sampai</label>
                <input wire:model.live="dateTo" type="date" class="input-pixel" style="width: 140px;">
            </div>
            @if($this->isFiltering)
                <button type="button" wire:click="resetFilters" class="btn-ghost" style="padding: 9px 12px;">
                    ✕ Reset
                </button>
            @endif
        </div>
        @if($this->isFiltering)
            <p class="font-sans text-stone text-xs mt-2">
                Mode filter aktif: navigasi bulan diabaikan{{ $dateFrom || $dateTo ? '' : ' untuk pencarian lintas periode gunakan rentang tanggal' }}. Menampilkan maks 200 hasil.
            </p>
        @endif
    </div>

    {{-- ── MONTH NAVIGATOR ── --}}
    <div class="flex items-center justify-between mb-3 {{ $this->isFiltering ? 'opacity-40 pointer-events-none' : '' }}">
        <button type="button" wire:click="prevMonth" class="btn-icon" title="Prev month">
            <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </button>
        <span class="font-sans font-semibold text-soil-dark text-sm">{{ \Carbon\Carbon::create($viewYear, $viewMonth)->format('F Y') }}</span>
        <button type="button" wire:click="nextMonth" class="btn-icon" title="Next month">
            <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4"><path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </button>
    </div>

    {{-- ── FILTER ── --}}
    <div class="flex gap-1.5 mb-4">
        @foreach(['all' => 'All', 'in' => '↑ Income', 'out' => '↓ Expense'] as $val => $label)
            <button type="button" wire:click="$set('filterType', '{{ $val }}')"
                class="filter-btn {{ $filterType === $val ? 'is-active' : '' }}">{{ $label }}</button>
        @endforeach
    </div>

    {{-- ── ENTRIES ── --}}
    <div class="space-y-1.5 flex-1 max-h-[500px] overflow-y-auto pr-1">
        @forelse($this->entries as $entry)
            <div class="card-item flex items-center gap-3 px-3 py-2.5
                {{ $entry->type === 'in' ? 'border-l-3 !border-l-grass' : 'border-l-3 !border-l-berry/60' }}">

                <div class="flex-shrink-0 w-7 h-7 flex items-center justify-center
                    {{ $entry->type === 'in' ? 'bg-grass-light/40 text-grass-dark' : 'bg-berry-light/40 text-berry-dark' }}"
                    style="border-radius: 4px;">
                    <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5">
                        @if($entry->type === 'in')
                            <path d="M12 19V5M5 12l7-7 7 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                        @else
                            <path d="M12 5v14M19 12l-7 7-7-7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                        @endif
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-sans font-semibold text-soil-dark text-sm">{{ ucfirst($entry->category) }}</span>
                        <span class="tag-sky">{{ $entry->recorded_at->format('d M') }}</span>
                    </div>
                    @if($entry->description)
                        <p class="font-sans text-soil text-xs mt-0.5 truncate">{{ $entry->description }}</p>
                    @endif
                </div>

                <span class="font-mono font-semibold flex-shrink-0 {{ $entry->type === 'in' ? 'text-grass-dark' : 'text-berry-dark' }}" style="font-size: 13px;">
                    {{ $entry->type === 'in' ? '+' : '-' }}Rp {{ number_format($entry->amount, 0, ',', '.') }}
                </span>

                <div class="flex gap-1 flex-shrink-0">
                    <button type="button" wire:click="startEdit({{ $entry->id }})" class="btn-icon text-sky-dark" title="Edit">
                        <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5">
                            <path d="M11 4H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-6m-9-2l9-9 3 3-9 9h-3z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <button type="button" wire:click="delete({{ $entry->id }})" wire:confirm="Hapus entri ini?" class="btn-icon text-berry" title="Delete">
                        <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5">
                            <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        @empty
            <x-empty-state variant="chest" title="NO ENTRIES">
                Press <span class="font-semibold text-grass-dark">+ New Entry</span> to start
            </x-empty-state>
        @endforelse
    </div>
</div>

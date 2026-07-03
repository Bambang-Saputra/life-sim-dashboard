<div
    x-data="{ showForm: @entangle('showForm') }"
    @budget-saved.window="showForm = false"
    class="panel flex flex-col"
>
    {{-- ── HEADER ── --}}
    <div class="flex items-start justify-between mb-5 gap-4 flex-wrap">
        <div>
            <h2 class="section-title mb-1">
                <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-berry-dark">
                    <path d="M12 3l7 4v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V7l7-4z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    <path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                BUDGET BOARD
            </h2>
            <p class="font-sans text-soil text-xs">Limit bulanan per kategori — HP bar keuanganmu untuk {{ now()->translatedFormat('F Y') }}.</p>
        </div>
        <button type="button" @click="showForm = !showForm" class="btn-primary flex-shrink-0">
            <span x-show="!showForm">+ Budget Baru</span>
            <span x-show="showForm">✕ Cancel</span>
        </button>
    </div>

    {{-- ── FORM ── --}}
    <div x-show="showForm" x-collapse>
        <form wire:submit.prevent="save" class="mb-5 p-4 bg-cream/50 border border-cream-dark" style="border-radius: 6px;">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="field-label">Kategori *</label>
                    <input wire:model="category" type="text" class="input-pixel"
                           placeholder="Food, Transport, Shopping..." list="budget-cat-list">
                    <datalist id="budget-cat-list">
                        @foreach($this->unbudgetedCategories as $cat)
                            <option value="{{ $cat }}">
                        @endforeach
                    </datalist>
                    @error('category') <p class="font-sans text-berry text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label">Limit / bulan (Rp) *</label>
                    <input wire:model="monthly_limit" type="number" min="1000" step="50000"
                           placeholder="1500000" class="input-pixel">
                    @error('monthly_limit') <p class="font-sans text-berry text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label">Icon</label>
                    <input wire:model="icon" type="text" maxlength="4" placeholder="🍜 🚌 🛒" class="input-pixel">
                </div>
                <div class="md:col-span-3 flex gap-2">
                    <button type="submit" wire:loading.attr="disabled" wire:target="save" class="btn-primary">
                        <span wire:loading.remove wire:target="save">{{ $editingId ? '💾 Update' : '✚ Pasang Budget' }}</span>
                        <span wire:loading wire:target="save" class="animate-blink">Saving...</span>
                    </button>
                    <button type="button" wire:click="cancelForm" class="btn-ghost">Cancel</button>
                </div>
            </div>
        </form>
    </div>

    {{-- ── DAFTAR HP BAR ── --}}
    <div class="space-y-3 flex-1">
        @forelse($this->budgets as $b)
            <div class="card-item p-3 {{ $b->status === 'over' ? '!border-berry/60' : '' }}">
                <div class="flex items-center gap-3">
                    <span class="text-xl flex-shrink-0">{{ $b->icon }}</span>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-baseline justify-between gap-2 mb-1.5 flex-wrap">
                            <span class="font-sans font-semibold text-soil-dark text-sm">{{ ucfirst($b->category) }}</span>
                            <span class="font-mono text-xs tabular-nums {{ $b->status === 'over' ? 'text-berry-dark font-bold' : 'text-soil' }}">
                                Rp {{ number_format($b->spent, 0, ',', '.') }}
                                <span class="text-stone">/ {{ number_format($b->monthly_limit, 0, ',', '.') }}</span>
                            </span>
                        </div>

                        {{-- HP bar pixel --}}
                        <div class="hp-track">
                            <div class="hp-fill hp-{{ $b->status }} {{ $b->status === 'over' ? 'animate-blink' : '' }}"
                                 style="width: {{ $b->percent }}%;"></div>
                        </div>

                        <div class="flex items-center justify-between mt-1">
                            <span class="font-pixel {{ $b->status === 'over' ? 'text-berry-dark' : ($b->status === 'warning' ? 'text-corn-dark' : 'text-grass-dark') }}"
                                  style="font-size: 7px;">
                                {{ $b->raw_percent }}%
                            </span>
                            <span class="font-sans text-xs {{ $b->remaining < 0 ? 'text-berry-dark font-semibold' : 'text-stone' }}">
                                @if($b->remaining >= 0)
                                    Sisa Rp {{ number_format($b->remaining, 0, ',', '.') }}
                                @else
                                    OVER Rp {{ number_format(abs($b->remaining), 0, ',', '.') }}!
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1 flex-shrink-0">
                        <button type="button" wire:click="startEdit({{ $b->id }})" class="btn-icon text-sky-dark" title="Edit">
                            <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5">
                                <path d="M11 4H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-6m-9-2l9-9 3 3-9 9h-3z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <button type="button" wire:click="delete({{ $b->id }})" wire:confirm="Hapus budget {{ $b->category }}?"
                                class="btn-icon text-berry" title="Hapus">
                            <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5">
                                <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <p class="font-pixel text-soil" style="font-size: 9px;">BELUM ADA BUDGET</p>
                <p class="font-sans text-stone text-sm mt-2">
                    Pasang limit per kategori — pengeluaranmu jadi HP bar yang kelihatan menipis.
                </p>
                @if(count($this->unbudgetedCategories))
                    <p class="font-sans text-stone text-xs mt-2">
                        Kandidat: {{ implode(', ', array_slice($this->unbudgetedCategories, 0, 4)) }}
                    </p>
                @endif
            </div>
        @endforelse
    </div>
</div>

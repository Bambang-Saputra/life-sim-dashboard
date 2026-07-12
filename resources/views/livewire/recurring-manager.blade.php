<div
    x-data="{ showForm: @entangle('showForm') }"
    @recurring-saved.window="showForm = false"
    class="panel flex flex-col"
>
    {{-- ── HEADER ── --}}
    <div class="flex items-start justify-between mb-5 gap-4 flex-wrap">
        <div>
            <h2 class="section-title mb-1">
                <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-sky-dark">
                    <path d="M4 12a8 8 0 0 1 14-5.3M20 12a8 8 0 0 1-14 5.3M18 3v4h-4M6 21v-4h4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                LANGGANAN RUTIN
            </h2>
            <p class="font-sans text-soil text-xs">Transaksi bulanan otomatis: kos, internet, Netflix, gaji.</p>
        </div>
        <button type="button" @click="showForm = !showForm" class="btn-primary flex-shrink-0">
            <span x-show="!showForm">+ Rutin Baru</span>
            <span x-show="showForm">✕ Cancel</span>
        </button>
    </div>

    {{-- Notifikasi auto-post --}}
    @if($justPosted > 0)
        <div class="insight-card insight-success mb-4">
            <p class="font-sans text-sm text-soil-dark">
                ⚡ <strong>{{ $justPosted }} transaksi rutin</strong> otomatis diposting untuk bulan ini.
            </p>
        </div>
    @endif

    {{-- ── FORM ── --}}
    <div x-show="showForm" x-collapse>
        <form wire:submit.prevent="save" class="mb-5 p-4 bg-cream/50 border border-cream-dark" style="border-radius: 6px;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="field-label">Tipe *</label>
                    <select wire:model="type" class="input-pixel">
                        <option value="out">↓ Expense (tagihan, langganan)</option>
                        <option value="in">↑ Income (gaji, passive income)</option>
                    </select>
                </div>
                <div>
                    <label class="field-label">Jumlah (Rp) *</label>
                    <input wire:model="amount" type="number" min="100" step="1000" placeholder="54000" class="input-pixel">
                    @error('amount') <p class="font-sans text-berry text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label">Kategori *</label>
                    <input wire:model="category" type="text" placeholder="Subscription, Rent, Salary..." class="input-pixel">
                    @error('category') <p class="font-sans text-berry text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label">Tiap tanggal *</label>
                    <input wire:model="day_of_month" type="number" min="1" max="31" class="input-pixel">
                    <p class="font-sans text-stone text-xs mt-1">Tanggal 31 otomatis menyesuaikan bulan pendek.</p>
                    @error('day_of_month') <p class="font-sans text-berry text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="field-label">Deskripsi</label>
                    <input wire:model="description" type="text" placeholder="Netflix family plan..." class="input-pixel">
                </div>
                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" wire:loading.attr="disabled" wire:target="save" class="btn-primary">
                        <span wire:loading.remove wire:target="save">{{ $editingId ? '💾 Update' : '✚ Aktifkan' }}</span>
                        <span wire:loading wire:target="save" class="animate-blink">Saving...</span>
                    </button>
                    <button type="button" wire:click="cancelForm" class="btn-ghost">Cancel</button>
                </div>
            </div>
        </form>
    </div>

    {{-- ── DAFTAR TEMPLATE ── --}}
    <div class="space-y-2 flex-1">
        @forelse($this->templates as $t)
            <div class="card-item flex items-center gap-3 px-3 py-2.5 {{ ! $t->is_active ? 'opacity-50' : '' }}
                {{ $t->type === 'in' ? 'border-l-2 !border-l-grass' : 'border-l-2 !border-l-berry/60' }}">

                <span class="font-pixel flex-shrink-0 {{ $t->type === 'in' ? 'text-grass-dark' : 'text-berry' }}" style="font-size: 9px;">
                    {{ $t->type === 'in' ? '↑' : '↓' }}
                </span>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-sans font-semibold text-soil-dark text-sm">{{ ucfirst($t->category) }}</span>
                        <span class="tag-soil">tiap tgl {{ $t->day_of_month }}</span>
                        @if($t->posted_this_month)
                            <span class="tag-grass">✓ bulan ini</span>
                        @elseif($t->is_active)
                            <span class="tag-sky">menunggu</span>
                        @else
                            <span class="tag-berry">nonaktif</span>
                        @endif
                    </div>
                    @if($t->description)
                        <p class="font-sans text-soil text-xs mt-0.5 truncate">{{ $t->description }}</p>
                    @endif
                </div>

                <span class="font-mono font-semibold flex-shrink-0 text-sm {{ $t->type === 'in' ? 'text-grass-dark' : 'text-berry-dark' }}">
                    {{ $t->type === 'in' ? '+' : '-' }}Rp {{ number_format($t->amount, 0, ',', '.') }}
                </span>

                <div class="flex gap-1 flex-shrink-0">
                    <button type="button" wire:click="toggleActive({{ $t->id }})"
                            class="btn-icon {{ $t->is_active ? 'text-grass-dark' : 'text-stone' }}"
                            title="{{ $t->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                        <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5">
                            @if($t->is_active)
                                <path d="M10 9v6m4-6v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                            @else
                                <path d="M10 8l6 4-6 4V8z" fill="currentColor"/>
                                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                            @endif
                        </svg>
                    </button>
                    <button type="button" wire:click="startEdit({{ $t->id }})" class="btn-icon text-sky-dark" title="Edit">
                        <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5">
                            <path d="M11 4H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-6m-9-2l9-9 3 3-9 9h-3z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <button type="button" wire:click="delete({{ $t->id }})" wire:confirm="Hapus langganan {{ $t->category }}? Transaksi yang sudah diposting tidak ikut terhapus."
                            class="btn-icon text-berry" title="Hapus">
                        <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5">
                            <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <p class="font-pixel text-soil" style="font-size: 9px;">BELUM ADA LANGGANAN</p>
                <p class="font-sans text-stone text-sm mt-2">
                    Daftarkan tagihan bulanan sekali, tiap bulan otomatis tercatat di ledger.
                </p>
            </div>
        @endforelse
    </div>
</div>

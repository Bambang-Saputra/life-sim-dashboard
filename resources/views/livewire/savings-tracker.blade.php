<div
    x-data="{
        showSavingForm: @entangle('showSavingForm'),
        showDepositForm: @entangle('showDepositForm')
    }"
    class="panel flex flex-col"
>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
        <div>
            <h2 class="section-title mb-2">
                <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-corn-dark">
                    <path d="M3 7h18l-2 13H5L3 7zm3 0V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2M9 11v6M15 11v6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                TABUNGAN
            </h2>
            <div class="flex items-baseline gap-2">
                <span class="font-sans text-soil text-xs">Total tabungan</span>
                <span class="font-pixel text-corn-dark" style="font-size: 12px;">
                    Rp {{ number_format($this->totalSaved, 0, ',', '.') }}
                </span>
            </div>
        </div>
        <button type="button" @click="showSavingForm = !showSavingForm" class="btn-primary flex-shrink-0">
            <span x-show="!showSavingForm">+ Tabungan Baru</span>
            <span x-show="showSavingForm">✕ Cancel</span>
        </button>
    </div>

    {{-- Monthly chart --}}
    @php $monthly = $this->monthlyDeposits; @endphp
    <div class="mb-5">
        <p class="font-sans font-semibold text-soil text-xs uppercase tracking-wider mb-2">Setoran 6 Bulan Terakhir</p>
        <div class="bg-cream/30 border border-cream-dark p-3" style="border-radius: 6px;">
            <div
                wire:ignore
                wire:key="savings-bar"
                x-data='barChart({
                    data: {
                        labels: @json($monthly["months"]),
                        datasets: [{
                            label: "Total Setoran",
                            data: @json($monthly["totals"]),
                            backgroundColor: "#E5B567"
                        }]
                    },
                    options: { plugins: { legend: { display: false } } }
                })'
                style="height: 160px;"
            >
                <canvas x-ref="canvas"></canvas>
            </div>
        </div>
    </div>

    {{-- Saving form --}}
    <div x-show="showSavingForm" x-collapse>
        <form wire:submit.prevent="saveSaving"
              class="form-shell">
            <div class="form-shell-header">
                <div>
                    <p class="form-shell-title">
                        <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-corn-dark">
                            <path d="M3 7h18l-2 13H5L3 7zm3 0V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2M9 11v6M15 11v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        {{ $editingSavingId ? 'Edit Saving Goal' : 'Create Saving Goal' }}
                    </p>
                    <p class="form-shell-subtitle">Pisahkan target tabungan agar progress bulanan lebih mudah dipantau.</p>
                </div>
                <span class="tag-corn">Goal</span>
            </div>
            <p class="hidden" style="font-size: 9px;">
                {{ $editingSavingId ? '✏ EDIT TABUNGAN' : '✚ TABUNGAN BARU' }}
            </p>

            <div class="form-shell-body grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="md:col-span-2">
                    <label class="field-label">Nama Tabungan *</label>
                    <input wire:model="name" type="text" placeholder="e.g. Liburan Bali, Beli motor..." class="input-pixel">
                    @error('name') <p class="font-sans text-berry text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="field-label">Target Amount (Rp)</label>
                    <input wire:model="target_amount" type="number" min="0" step="100000" placeholder="5000000" class="input-pixel">
                </div>

                <div>
                    <label class="field-label">Target Tanggal</label>
                    <input wire:model="target_date" type="date" class="input-pixel">
                </div>

                <div>
                    <label class="field-label">Icon</label>
                    <input wire:model="icon" type="text" maxlength="4" placeholder="💰 🏖 🏍 🎁" class="input-pixel">
                </div>

                <div>
                    <label class="field-label">Warna</label>
                    <select wire:model="color" class="input-pixel">
                        <option value="grass">🟢 Hijau</option>
                        <option value="corn">🟡 Kuning</option>
                        <option value="sky">🔵 Biru</option>
                        <option value="berry">🔴 Merah</option>
                        <option value="soil">🟤 Cokelat</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="field-label">Catatan</label>
                    <textarea wire:model="note" rows="2" placeholder="Untuk apa tabungan ini..." class="input-pixel"></textarea>
                </div>

                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" wire:loading.attr="disabled" wire:target="saveSaving" class="btn-primary">
                        <span wire:loading.remove wire:target="saveSaving">{{ $editingSavingId ? '💾 Update' : '✚ Buat Tabungan' }}</span>
                        <span wire:loading wire:target="saveSaving" class="animate-blink">Saving...</span>
                    </button>
                    <button type="button" @click="showSavingForm = false" wire:click="cancelSavingForm" class="btn-ghost">Cancel</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Deposit form --}}
    <div x-show="showDepositForm" x-collapse>
        <form wire:submit.prevent="saveDeposit"
              class="mb-5 p-4 bg-grass-light/30 border border-grass/40"
              style="border-radius: 6px;">
            <p class="section-title mb-4 text-grass-dark" style="font-size: 9px;">
                {{ $editingDepositId ? '✏ EDIT SETORAN' : '💸 SETOR / TARIK' }}
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="field-label">Jumlah (Rp) *</label>
                    <input wire:model="depositAmount" type="number" step="1000" placeholder="+ untuk setor, - untuk tarik" class="input-pixel">
                    <p class="font-sans text-stone text-xs mt-1">Positif = setor · Negatif = tarik</p>
                    @error('depositAmount') <p class="font-sans text-berry text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="field-label">Tanggal *</label>
                    <input wire:model="depositDate" type="date" class="input-pixel">
                </div>

                <div class="md:col-span-2">
                    <label class="field-label">Catatan</label>
                    <input wire:model="depositNote" type="text" placeholder="Catatan singkat..." class="input-pixel">
                </div>

                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" wire:loading.attr="disabled" wire:target="saveDeposit" class="btn-primary">
                        <span wire:loading.remove wire:target="saveDeposit">💾 Simpan</span>
                        <span wire:loading wire:target="saveDeposit" class="animate-blink">Saving...</span>
                    </button>
                    <button type="button" @click="showDepositForm = false" wire:click="cancelDepositForm" class="btn-ghost">Cancel</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Savings list --}}
    <div class="space-y-3 flex-1">
        @forelse($this->savings as $saving)
            @php
                $current = (float) ($saving->current_amount_raw ?? 0);
                $target  = (float) $saving->target_amount;
                $percent = $target > 0 ? min(100, (int) round(($current / $target) * 100)) : 0;
                $colorMap = [
                    'grass' => ['bg' => 'bg-grass-light/30', 'border' => 'border-grass/40', 'text' => 'text-grass-dark', 'fill' => 'bg-grass'],
                    'corn'  => ['bg' => 'bg-corn-light/40',  'border' => 'border-corn/40',  'text' => 'text-corn-dark',  'fill' => 'bg-corn-dark'],
                    'sky'   => ['bg' => 'bg-sky-light/30',   'border' => 'border-sky/40',   'text' => 'text-sky-dark',   'fill' => 'bg-sky'],
                    'berry' => ['bg' => 'bg-berry-light/30', 'border' => 'border-berry/40', 'text' => 'text-berry-dark', 'fill' => 'bg-berry'],
                    'soil'  => ['bg' => 'bg-cream',          'border' => 'border-soil/30',  'text' => 'text-soil-dark',  'fill' => 'bg-soil'],
                ];
                $c = $colorMap[$saving->color] ?? $colorMap['grass'];
            @endphp

            <div class="p-4 {{ $c['bg'] }} border {{ $c['border'] }}" style="border-radius: 6px;">
                <div class="flex items-start gap-3">
                    <div class="text-3xl flex-shrink-0">{{ $saving->icon }}</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2 mb-1 flex-wrap">
                            <h3 class="font-sans font-semibold text-soil-dark text-base">{{ $saving->name }}</h3>
                            <div class="flex gap-1">
                                <button type="button" wire:click="startDeposit({{ $saving->id }})"
                                    class="font-sans font-semibold {{ $c['text'] }} text-xs px-2 py-1 bg-white/60 hover:bg-white border border-cream-dark"
                                    style="border-radius: 4px;">+ Setor</button>
                                <button type="button" wire:click="startEditSaving({{ $saving->id }})" class="btn-icon">
                                    <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5"><path d="M11 4H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-6m-9-2l9-9 3 3-9 9h-3z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                </button>
                                <button type="button" wire:click="deleteSaving({{ $saving->id }})" wire:confirm="Hapus tabungan ini beserta semua setorannya?"
                                    class="btn-icon text-berry">
                                    <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                </button>
                            </div>
                        </div>

                        @if($saving->note)
                            <p class="font-sans text-soil text-xs mb-2">{{ $saving->note }}</p>
                        @endif

                        <div class="flex items-baseline gap-2 mb-2 flex-wrap">
                            <span class="font-pixel {{ $c['text'] }}" style="font-size: 12px;">
                                Rp {{ number_format($current, 0, ',', '.') }}
                            </span>
                            @if($target > 0)
                                <span class="font-sans text-stone text-xs">/ Rp {{ number_format($target, 0, ',', '.') }}</span>
                            @endif
                            @if($saving->target_date)
                                <span class="font-sans text-stone text-xs ml-auto">🎯 {{ $saving->target_date->format('d M Y') }}</span>
                            @endif
                        </div>

                        @if($target > 0)
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 bg-white/70 border border-cream-dark overflow-hidden" style="border-radius: 999px;">
                                    <div class="h-full {{ $c['fill'] }} transition-all duration-500" style="width: {{ $percent }}%;"></div>
                                </div>
                                <span class="font-mono text-xs {{ $c['text'] }} tabular-nums font-semibold" style="min-width: 36px; text-align: right;">{{ $percent }}%</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <x-empty-state variant="piggy" title="BELUM ADA TABUNGAN">
                Buat tabungan untuk goals seperti liburan, beli motor, dll
            </x-empty-state>
        @endforelse
    </div>

    {{-- ── RIWAYAT SETORAN (edit / hapus) ── --}}
    @if($this->recentDeposits->isNotEmpty())
        <div class="mt-5 pt-4 border-t border-cream-dark">
            <button type="button" wire:click="$toggle('showHistory')"
                    class="font-sans font-semibold text-soil text-xs uppercase tracking-wider flex items-center gap-1.5 hover:text-soil-dark transition-colors">
                <span>{{ $showHistory ? '▼' : '▶' }}</span>
                Riwayat Setoran ({{ $this->recentDeposits->count() }} terbaru)
            </button>

            @if($showHistory)
                <div class="space-y-1.5 mt-3">
                    @foreach($this->recentDeposits as $d)
                        <div class="card-item flex items-center gap-2.5 px-3 py-2
                            {{ $d->amount >= 0 ? 'border-l-2 !border-l-grass' : 'border-l-2 !border-l-berry/60' }}">
                            <span class="flex-shrink-0 text-base">{{ $d->goal?->icon ?? '💰' }}</span>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-sans font-medium text-soil-dark text-sm truncate">{{ $d->goal?->name ?? '(goal terhapus)' }}</span>
                                    <span class="tag-sky">{{ $d->deposited_at->format('d M Y') }}</span>
                                </div>
                                @if($d->note)
                                    <p class="font-sans text-stone text-xs mt-0.5 truncate">{{ $d->note }}</p>
                                @endif
                            </div>
                            <span class="font-mono font-semibold text-sm flex-shrink-0 {{ $d->amount >= 0 ? 'text-grass-dark' : 'text-berry-dark' }}">
                                {{ $d->amount >= 0 ? '+' : '-' }}Rp {{ number_format(abs($d->amount), 0, ',', '.') }}
                            </span>
                            <div class="flex gap-1 flex-shrink-0">
                                <button type="button" wire:click="startEditDeposit({{ $d->id }})" class="btn-icon text-sky-dark" title="Edit setoran">
                                    <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5">
                                        <path d="M11 4H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-6m-9-2l9-9 3 3-9 9h-3z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                                <button type="button" wire:click="deleteDeposit({{ $d->id }})"
                                        wire:confirm="Hapus setoran ini? Saldo goal akan berkurang."
                                        class="btn-icon text-berry" title="Hapus setoran">
                                    <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5">
                                        <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>

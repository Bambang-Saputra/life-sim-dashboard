<div
    x-data="{ showForm: @entangle('showForm') }"
    @habit-saved.window="showForm = false"
    class="panel flex flex-col"
>
    {{-- ── HEADER ── --}}
    <div class="flex items-start justify-between mb-5 gap-4 flex-wrap">
        <div>
            <h2 class="section-title mb-1">
                <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-grass-dark">
                    <path d="M4 12a8 8 0 0 1 14-5.3M20 12a8 8 0 0 1-14 5.3M18 3v4h-4M6 21v-4h4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                HABIT HARIAN
            </h2>
            <p class="font-sans text-soil text-xs">Quest yang otomatis muncul kembali setiap hari — mesin streak-mu.</p>
        </div>
        <button type="button" @click="showForm = !showForm" class="btn-primary flex-shrink-0">
            <span x-show="!showForm">+ Habit Baru</span>
            <span x-show="showForm">✕ Cancel</span>
        </button>
    </div>

    {{-- ── FORM ── --}}
    <div x-show="showForm" x-collapse>
        <form wire:submit.prevent="save" class="mb-5 p-4 bg-cream/50 border border-cream-dark" style="border-radius: 6px;">
            <div class="grid grid-cols-1 gap-3">
                <div>
                    <label class="field-label">Nama Habit *</label>
                    <input wire:model="title" type="text" placeholder="Olahraga pagi, baca 10 halaman..." class="input-pixel">
                    @error('title') <p class="font-sans text-berry text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="field-label">Kategori</label>
                        <input wire:model="category" type="text" placeholder="health, learning..." class="input-pixel">
                    </div>
                    <div>
                        <label class="field-label">Difficulty</label>
                        <select wire:model="priority" class="input-pixel">
                            <option value="easy">🌱 Easy · 10 XP</option>
                            <option value="normal">⚔ Normal · 20 XP</option>
                            <option value="hard">💀 Hard · 50 XP</option>
                            <option value="legendary">★ Legendary · 100 XP</option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit" wire:loading.attr="disabled" wire:target="save" class="btn-primary">
                        <span wire:loading.remove wire:target="save">{{ $editingId ? '💾 Update' : '✚ Mulai Habit' }}</span>
                        <span wire:loading wire:target="save" class="animate-blink">Saving...</span>
                    </button>
                    <button type="button" wire:click="cancelForm" class="btn-ghost">Cancel</button>
                </div>
            </div>
        </form>
    </div>

    {{-- ── DAFTAR HABIT ── --}}
    <div class="space-y-2 flex-1">
        @forelse($this->habits as $h)
            <div class="card-item flex items-center gap-3 px-3 py-2.5 {{ ! $h->is_active ? 'opacity-50' : '' }} priority-{{ $h->priority }}">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-sans font-semibold text-soil-dark text-sm">{{ $h->title }}</span>
                        @if($h->category)<span class="tag-soil">{{ $h->category }}</span>@endif
                        @if($h->spawned_today)
                            <span class="tag-grass">✓ hari ini</span>
                        @elseif($h->is_active)
                            <span class="tag-sky">besok pagi</span>
                        @else
                            <span class="tag-berry">jeda</span>
                        @endif
                    </div>
                </div>

                <div class="flex gap-1 flex-shrink-0">
                    <button type="button" wire:click="toggleActive({{ $h->id }})"
                            class="btn-icon {{ $h->is_active ? 'text-grass-dark' : 'text-stone' }}"
                            title="{{ $h->is_active ? 'Jeda habit' : 'Aktifkan lagi' }}">
                        <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5">
                            @if($h->is_active)
                                <path d="M10 9v6m4-6v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                            @else
                                <path d="M10 8l6 4-6 4V8z" fill="currentColor"/>
                                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                            @endif
                        </svg>
                    </button>
                    <button type="button" wire:click="startEdit({{ $h->id }})" class="btn-icon text-sky-dark" title="Edit">
                        <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5">
                            <path d="M11 4H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-6m-9-2l9-9 3 3-9 9h-3z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <button type="button" wire:click="delete({{ $h->id }})"
                            wire:confirm="Hapus habit {{ $h->title }}? Quest yang sudah muncul tidak ikut terhapus."
                            class="btn-icon text-berry" title="Hapus">
                        <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5">
                            <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <p class="font-pixel text-soil" style="font-size: 9px;">BELUM ADA HABIT</p>
                <p class="font-sans text-stone text-sm mt-2">
                    Buat kebiasaan harian — tiap pagi otomatis jadi quest baru, selesaikan untuk menjaga streak 🔥
                </p>
            </div>
        @endforelse
    </div>
</div>

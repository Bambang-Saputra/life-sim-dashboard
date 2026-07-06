<div
    x-data="{
        showForm: @entangle('showForm'),
        ...questUI()
    }"
    x-init="initNotifications(); listenForAlarms()"
    @quest-completed.window="onQuestCompleted($event.detail)"
    @quest-saved.window="showForm = false"
    class="panel flex flex-col"
>

    {{-- ── HEADER ── --}}
    <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
        <div class="flex items-center gap-3 flex-wrap">
            <h2 class="section-title">
                <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-grass-dark">
                    <path d="M14.7 6.3L7 14l-2 5 5-2 7.7-7.7-3-3z M11 21h10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                QUEST BOARD
            </h2>
            @php $ps = $this->playerStats; @endphp
            <div class="flex items-center gap-1.5 flex-wrap">
                <span class="font-pixel bg-corn-dark text-white px-2 py-1" style="border-radius: 4px; font-size: 8px;"
                      title="{{ $ps['xp_into_level'] }}/100 XP menuju level berikutnya">LV.{{ $ps['level'] }}</span>
                <span class="font-sans font-semibold text-grass-dark bg-grass-light/40 border border-grass/30 px-2.5 py-0.5 text-xs flex items-center gap-1"
                      style="border-radius: 999px;">
                    <svg viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/></svg>
                    {{ number_format($ps['xp']) }} XP
                </span>
                @if($ps['streak'] > 0)
                    <span class="font-sans font-semibold text-berry-dark bg-berry-light/30 border border-berry/30 px-2.5 py-0.5 text-xs"
                          style="border-radius: 999px;"
                          title="Rekor terpanjang: {{ $ps['longest_streak'] }} hari">
                        🔥 {{ $ps['streak'] }} hari
                    </span>
                @endif
            </div>
        </div>
        <button type="button" @click="showForm = !showForm" class="btn-primary">
            <span x-show="!showForm">+ New Quest</span>
            <span x-show="showForm">✕ Cancel</span>
        </button>
    </div>

    {{-- ── LEVEL UP TOAST ── --}}
    <div
        x-show="levelUpMessage"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-3"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        class="fixed top-20 right-5 z-50 bg-soil-dark border border-grass-dark px-5 py-3 shadow-cozy-lg"
        style="border-radius: 6px;"
    >
        <p class="font-pixel text-grass-light" style="font-size: 8px;">★ QUEST COMPLETE!</p>
        <p class="font-sans font-medium text-cream-light text-sm mt-1" x-text="levelUpMessage"></p>
    </div>

    {{-- ── FORM (real <form> with wire:submit) ── --}}
    <div x-show="showForm" x-collapse>
        <form wire:submit.prevent="save"
              class="form-shell">

            <div class="form-shell-header">
                <div>
                    <p class="form-shell-title">
                        <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-grass-dark">
                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        {{ $editingId ? 'Edit Quest' : 'Create New Quest' }}
                    </p>
                    <p class="form-shell-subtitle">
                        Atur prioritas, deadline, progress, dan catatan dampak dalam satu form.
                    </p>
                </div>
                <span class="tag-grass">{{ $editingId ? 'Update mode' : 'New entry' }}</span>
            </div>

            <p class="hidden" style="font-size: 9px;">
                {{ $editingId ? '✏ EDIT QUEST' : '✚ NEW QUEST' }}
            </p>

            <div class="form-shell-body grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="md:col-span-2">
                    <label class="field-label">Quest Name *</label>
                    <input wire:model="title" type="text"
                        placeholder="Contoh: Review pengeluaran minggu ini" class="input-pixel" required>
                    @error('title') <p class="font-sans text-berry text-xs mt-1">⚠ {{ $message }}</p> @enderror
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

                <div>
                    <label class="field-label">Category</label>
                    <input wire:model="category" type="text"
                        placeholder="work, health, finance, learning..." class="input-pixel">
                </div>

                <div>
                    <label class="field-label">Due Date</label>
                    <input wire:model="due_at" type="datetime-local" class="input-pixel">
                </div>

                <div>
                    <label class="field-label">Alarm</label>
                    <input wire:model="alarm_at" type="datetime-local" class="input-pixel">
                </div>

                <div class="md:col-span-2">
                    <label class="field-label">Description</label>
                    <textarea wire:model="description" rows="2"
                        placeholder="Ringkasan tugas yang harus dilakukan..." class="input-pixel"></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="field-label">History / Notes</label>
                    <textarea wire:model="history" rows="3"
                        placeholder="Catatan progress, keputusan, hasil, atau dampak dari tugas ini..." class="input-pixel"></textarea>
                </div>

                <div>
                    <label class="field-label">Progress (<span x-text="$wire.progress">{{ $progress }}</span>%)</label>
                    <input wire:model.live="progress" type="range" min="0" max="100" step="5" class="w-full accent-grass-dark">
                </div>

                <div class="flex items-center gap-2 pt-6">
                    <input wire:model="is_important" type="checkbox" id="is_important"
                           class="w-4 h-4 border-2 border-cream-dark text-corn-dark focus:ring-corn"
                           style="border-radius: 3px;">
                    <label for="is_important" class="font-sans text-sm text-soil-dark cursor-pointer select-none">
                        ⭐ Tandai sebagai <strong>penting / berdampak</strong>
                    </label>
                </div>

                <div class="md:col-span-2 flex gap-2 pt-2 border-t border-cream-dark/50">
                    <button type="submit" wire:loading.attr="disabled" wire:target="save" class="btn-primary">
                        <span wire:loading.remove wire:target="save">
                            {{ $editingId ? '💾 Update Quest' : '✚ Add Quest' }}
                        </span>
                        <span wire:loading wire:target="save" class="animate-blink">Saving...</span>
                    </button>
                    <button type="button" wire:click="cancelForm" class="btn-ghost">Cancel</button>
                </div>
            </div>
        </form>
    </div>

    {{-- ── FILTER BAR ── --}}
    <div class="flex items-center justify-between mb-4 gap-3 flex-wrap">
        <div class="flex gap-1.5 flex-wrap">
            @foreach(['all' => 'All', 'pending' => 'Active', 'important' => '⭐ Penting', 'completed' => 'Done'] as $val => $label)
                <button type="button" wire:click="$set('filter', '{{ $val }}')"
                    class="filter-btn {{ $filter === $val ? 'is-active' : '' }}"
                >{{ $label }}</button>
            @endforeach
        </div>
        <select wire:model.live="sortBy" class="input-pixel py-1.5 px-2.5" style="width: auto; font-size: 11px;">
            <option value="due_at">Sort: Due Date</option>
            <option value="priority">Sort: Priority</option>
            <option value="created_at">Sort: Added</option>
        </select>
    </div>

    {{-- ── QUEST LIST ── --}}
    <div class="space-y-2">
        @forelse($this->quests as $quest)
            <div
                x-data="{
                    showDetails: false,
                    completed: {{ $quest->is_completed ? 'true' : 'false' }},
                    toggle(id) { this.completed = !this.completed; $wire.toggleComplete(id); }
                }"
                :class="{ 'opacity-50': completed }"
                class="card-item p-3 priority-{{ $quest->priority }}
                    {{ $quest->is_overdue && !$quest->is_completed ? '!border-berry/40' : '' }}"
                @if($quest->alarm_at)
                    data-alarm-time="{{ $quest->alarm_at->toIso8601String() }}"
                    data-quest-title="{{ $quest->title }}"
                @endif
            >
                <div class="flex items-start gap-3">
                    {{-- Checkbox --}}
                    <button type="button" @click="toggle({{ $quest->id }})"
                        class="flex-shrink-0 w-5 h-5 mt-0.5 border-2 flex items-center justify-center transition-colors duration-100"
                        style="border-radius: 4px;"
                        :class="completed ? 'bg-grass border-grass-dark' : 'bg-white border-cream-dark hover:border-grass'"
                    >
                        <svg x-show="completed" viewBox="0 0 16 16" fill="none" class="w-3 h-3 text-white">
                            <path d="M3 8l3 3 7-7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @if($quest->is_important)
                                <span class="text-corn-dark text-base" title="Penting">⭐</span>
                            @endif
                            <p class="font-sans font-semibold text-soil-dark text-sm transition-all"
                               :class="completed ? 'line-through text-stone' : ''">
                                {{ $quest->title }}
                            </p>
                        </div>
                        @if($quest->description)
                            <p class="font-sans text-soil text-xs mt-0.5">{{ $quest->description }}</p>
                        @endif

                        <div class="flex flex-wrap items-center gap-1.5 mt-2">
                            @php
                                $pTag = match($quest->priority) {
                                    'easy' => 'tag-grass', 'normal' => 'tag-sky',
                                    'hard' => 'tag-corn', 'legendary' => 'tag-berry',
                                    default => 'tag-soil',
                                };
                                $pLabel = match($quest->priority) {
                                    'legendary' => '★ Legendary', 'hard' => '💀 Hard',
                                    'normal' => '⚔ Normal', 'easy' => '🌱 Easy',
                                    default => $quest->priority,
                                };
                            @endphp
                            <span class="{{ $pTag }}">{{ $pLabel }}</span>
                            @if($quest->category)<span class="tag-soil">{{ $quest->category }}</span>@endif

                            @if($quest->due_at)
                                <div x-data="questAlarm('{{ $quest->alarm_at?->toIso8601String() ?? $quest->due_at->toIso8601String() }}')"
                                     class="inline-flex items-center gap-1">
                                    <span class="font-sans text-xs"
                                        :class="urgency === 'critical' ? 'text-berry font-semibold' : urgency === 'warning' ? 'text-corn-dark' : 'text-stone'"
                                        x-text="timeLeft || '{{ $quest->due_at->format('d M H:i') }}'"></span>
                                </div>
                            @endif

                            <span class="font-sans text-stone text-xs ml-auto">+{{ $quest->xp_reward }} XP</span>
                        </div>

                        <div class="mt-2 flex items-center gap-2">
                            <div class="progress-quest flex-1">
                                <div class="progress-quest-fill" style="width: {{ $quest->progress }}%;"></div>
                            </div>
                            <span class="font-mono text-xs text-soil tabular-nums" style="min-width: 30px; text-align: right;">{{ $quest->progress }}%</span>
                            <input type="number" min="0" max="100" step="5" value="{{ $quest->progress }}"
                                @change="$wire.updateProgress({{ $quest->id }}, parseInt($event.target.value))"
                                class="input-pixel py-0.5 px-1.5 text-xs" style="width: 56px; font-size: 11px;">
                        </div>

                        @if($quest->history)
                            <button type="button" @click="showDetails = !showDetails"
                                    class="font-sans text-xs text-sky-dark hover:text-sky-dark/70 mt-2 inline-flex items-center gap-1">
                                <span x-text="showDetails ? '▼' : '▶'"></span>
                                <span x-text="showDetails ? 'Sembunyikan history' : 'Lihat history'"></span>
                            </button>
                            <div x-show="showDetails" x-collapse class="mt-2 p-3 bg-cream/40 border border-cream-dark text-sm font-sans text-soil-dark whitespace-pre-wrap"
                                 style="border-radius: 4px;">
                                {{ $quest->history }}
                            </div>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col gap-1 flex-shrink-0">
                        <button type="button" wire:click="toggleImportant({{ $quest->id }})"
                            class="btn-icon {{ $quest->is_important ? 'text-corn-dark bg-corn-light/30' : 'text-stone' }}"
                            title="{{ $quest->is_important ? 'Hilangkan tanda penting' : 'Tandai penting' }}">
                            <svg viewBox="0 0 24 24" fill="{{ $quest->is_important ? 'currentColor' : 'none' }}" class="w-3.5 h-3.5">
                                <path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <button type="button" wire:click="startEdit({{ $quest->id }})" class="btn-icon text-sky-dark" title="Edit">
                            <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5">
                                <path d="M11 4H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-6m-9-2l9-9 3 3-9 9h-3z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <button type="button" wire:click="delete({{ $quest->id }})" wire:confirm="Abandon this quest?"
                            class="btn-icon text-berry" title="Delete">
                            <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5">
                                <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" class="w-10 h-10 text-cream-dark mx-auto mb-3">
                    <path d="M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="font-pixel text-soil" style="font-size: 9px;">NO QUESTS YET</p>
                <p class="font-sans text-stone text-sm mt-2">Press <span class="font-semibold text-grass-dark">+ New Quest</span> to start</p>
            </div>
        @endforelse
    </div>
</div>

<script>
function questUI() {
    return {
        totalXP: parseInt(localStorage.getItem('life_sim_xp') || '0'),
        levelUpMessage: null,
        notificationsGranted: false,
        async initNotifications() {
            if (!('Notification' in window)) return;
            if (Notification.permission === 'default') {
                const res = await Notification.requestPermission();
                this.notificationsGranted = res === 'granted';
            } else {
                this.notificationsGranted = Notification.permission === 'granted';
            }
        },
        listenForAlarms() {
            setInterval(() => {
                document.querySelectorAll('[data-alarm-time]').forEach(el => {
                    const diff = new Date(el.dataset.alarmTime) - new Date();
                    if (diff > 0 && diff <= 30000 && !el.dataset.notified) {
                        el.dataset.notified = 'true';
                        if (this.notificationsGranted) {
                            new Notification('⚔ Quest Reminder!', { body: `"${el.dataset.questTitle}" is due soon!` });
                        }
                    }
                });
            }, 30000);
        },
        onQuestCompleted(detail) {
            this.totalXP += detail.xpReward;
            localStorage.setItem('life_sim_xp', this.totalXP);
            this.levelUpMessage = `+${detail.xpReward} XP earned!`;
            setTimeout(() => this.levelUpMessage = null, 3000);
        },
    }
}
</script>

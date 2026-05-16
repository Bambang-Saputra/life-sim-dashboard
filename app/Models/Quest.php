<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quest extends Model
{
    use HasFactory;

    // ── PERSONAL APP: user_id dihapus dari fillable ──
    protected $fillable = [
        'title', 'description', 'history', 'is_completed', 'is_important',
        'priority', 'category', 'due_at', 'alarm_at',
        'xp_reward', 'progress', 'completed_at',
    ];

    // Cast otomatis agar kode lebih bersih
    protected $casts = [
        'is_completed' => 'boolean',
        'is_important' => 'boolean',
        'progress'     => 'integer',
        'due_at'       => 'datetime',
        'alarm_at'     => 'datetime',
        'completed_at' => 'datetime',
    ];

    // ── TIDAK ADA RELASI belongsTo(User::class) ──
    // Tidak ada tabel users, tidak ada user_id. Tidak perlu.

    // ── SCOPES ──

    // scopeForCurrentUser DIHAPUS — tidak relevan di personal app.
    // Ganti semua Quest::forCurrentUser() menjadi Quest::query()

    /** Filter quest yang belum selesai */
    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    /** Filter berdasarkan prioritas */
    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    // ── COMPUTED PROPERTIES (Accessor) ──

    /** Apakah quest sudah melewati batas waktu? */
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_at
            && !$this->is_completed
            && $this->due_at->isPast();
    }

    /** Warna berdasarkan prioritas untuk UI */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'easy'      => 'text-sdv-grass-dark',
            'normal'    => 'text-sdv-river-dark',
            'hard'      => 'text-sdv-oak-dark',
            'legendary' => 'text-sdv-barn animate-blink',
            default     => 'text-sdv-soil',
        };
    }
}

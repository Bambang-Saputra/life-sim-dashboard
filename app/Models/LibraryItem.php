<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LibraryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_type',
        'external_id',
        'title',
        'cover_image',
        'genre',
        'personal_rating',
        'personal_review',
        'status',
        'metadata',
    ];

    protected $casts = [
        'personal_rating' => 'decimal:1',
        'metadata'        => 'array',
    ];

    public function scopeByType($query, string $type)
    {
        return $query->where('api_type', $type);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRated($query)
    {
        return $query->whereNotNull('personal_rating');
    }

    public function getApiTypeLabelAttribute(): string
    {
        return match($this->api_type) {
            'movie' => '🎬 Film',
            'tv'    => '📺 Series',
            'anime' => '⛩ Anime',
            'manga' => '📖 Manga',
            default => $this->api_type,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'plan_to'   => '📋 Plan to Watch',
            'ongoing'   => '▶ Ongoing',
            'completed' => '✓ Completed',
            'dropped'   => '✕ Dropped',
            default     => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'plan_to'   => 'text-sdv-river',
            'ongoing'   => 'text-sdv-grass',
            'completed' => 'text-sdv-pine',
            'dropped'   => 'text-sdv-barn',
            default     => 'text-sdv-soil',
        };
    }
}

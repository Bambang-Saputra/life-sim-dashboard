<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class JikanService
{
    // Jikan tidak butuh API key, tapi ada rate limit: 3 req/detik, 60 req/menit
    private string $baseUrl = 'https://api.jikan.moe/v4';

    /**
     * Top anime untuk default Library view.
     * Cache 6 jam supaya hemat rate limit.
     */
    public function topAnime(int $limit = 12): array
    {
        return Cache::remember("jikan_top_anime_{$limit}", now()->addHours(6), function () use ($limit) {
            $response = Http::timeout(15)->retry(2, 1000)
                ->get("{$this->baseUrl}/top/anime", [
                    'limit'  => $limit,
                    'filter' => 'bypopularity',
                ]);

            if ($response->failed()) return [];

            return array_map(fn($item) => $this->normalizeAnime($item), $response->json('data', []));
        });
    }

    /**
     * Top manga untuk default Library view.
     */
    public function topManga(int $limit = 12): array
    {
        return Cache::remember("jikan_top_manga_{$limit}", now()->addHours(6), function () use ($limit) {
            $response = Http::timeout(15)->retry(2, 1000)
                ->get("{$this->baseUrl}/top/manga", [
                    'limit'  => $limit,
                    'filter' => 'bypopularity',
                ]);

            if ($response->failed()) return [];

            return array_map(fn($item) => $this->normalizeManga($item), $response->json('data', []));
        });
    }

    /** Internal — normalize 1 item anime */
    private function normalizeAnime(array $item): array
    {
        return [
            'external_id'  => $item['mal_id'],
            'api_type'     => 'anime',
            'title'        => $item['title_english'] ?? $item['title'] ?? 'Unknown',
            'cover_image'  => $item['images']['jpg']['image_url'] ?? null,
            'genre'        => implode(', ', array_column($item['genres'] ?? [], 'name')),
            'release_year' => $item['year'] ?? null,
            'overview'     => $item['synopsis'] ?? '',
            'mal_rating'   => $item['score'] ?? 0,
            'episodes'     => $item['episodes'] ?? null,
            'status'       => $item['status'] ?? 'Unknown',
            'metadata'     => [
                'type'    => $item['type'] ?? 'TV',
                'studios' => array_column($item['studios'] ?? [], 'name'),
                'season'  => $item['season'] ?? null,
                'rank'    => $item['rank'] ?? null,
            ],
        ];
    }

    /** Internal — normalize 1 item manga */
    private function normalizeManga(array $item): array
    {
        return [
            'external_id'  => $item['mal_id'],
            'api_type'     => 'manga',
            'title'        => $item['title_english'] ?? $item['title'] ?? 'Unknown',
            'cover_image'  => $item['images']['jpg']['image_url'] ?? null,
            'genre'        => implode(', ', array_column($item['genres'] ?? [], 'name')),
            'release_year' => substr($item['published']['from'] ?? '', 0, 4),
            'overview'     => $item['synopsis'] ?? '',
            'mal_rating'   => $item['score'] ?? 0,
            'chapters'     => $item['chapters'] ?? null,
            'volumes'      => $item['volumes'] ?? null,
            'metadata'     => [
                'authors' => array_column($item['authors'] ?? [], 'name'),
                'status'  => $item['status'] ?? 'Unknown',
                'rank'    => $item['rank'] ?? null,
            ],
        ];
    }

    /**
     * Cari anime berdasarkan keyword
     */
    public function searchAnime(string $query): array
    {
        return Cache::remember("jikan_anime_" . md5($query), now()->addHour(), function () use ($query) {
            // Jikan kadang lambat, beri timeout yang cukup
            $response = Http::timeout(15)
                ->retry(2, 1000) // Jeda 1 detik antar retry (respect rate limit)
                ->get("{$this->baseUrl}/anime", [
                    'q'      => $query,
                    'limit'  => 10,
                    'sfw'    => true, // Filter konten dewasa
                ]);

            if ($response->failed()) return [];

            return array_map(fn($item) => $this->normalizeAnime($item), $response->json('data', []));
        });
    }

    /**
     * Cari manga berdasarkan keyword
     */
    public function searchManga(string $query): array
    {
        return Cache::remember("jikan_manga_" . md5($query), now()->addHour(), function () use ($query) {
            $response = Http::timeout(15)
                ->retry(2, 1000)
                ->get("{$this->baseUrl}/manga", [
                    'q'     => $query,
                    'limit' => 10,
                    'sfw'   => true,
                ]);

            if ($response->failed()) return [];

            return array_map(fn($item) => $this->normalizeManga($item), $response->json('data', []));
        });
    }
}

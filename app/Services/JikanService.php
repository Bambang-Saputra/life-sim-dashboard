<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class JikanService
{
    // Jikan tidak butuh API key, tapi ada rate limit: 3 req/detik, 60 req/menit
    private string $baseUrl = 'https://api.jikan.moe/v4';

    /**
     * Top anime per-page. Cache 6 jam supaya hemat rate limit.
     */
    public function topAnime(int $page = 1, int $limit = 24): array
    {
        return Cache::remember("jikan_top_anime_p{$page}_l{$limit}", now()->addHours(6), function () use ($page, $limit) {
            $response = Http::timeout(15)->retry(2, 1000)
                ->get("{$this->baseUrl}/top/anime", [
                    'page'   => max(1, $page),
                    'limit'  => $limit,
                    'filter' => 'bypopularity',
                ]);

            if ($response->failed()) {
                return ['items' => [], 'current_page' => 1, 'total_pages' => 1, 'total_results' => 0];
            }

            $data = $response->json();
            return [
                'items'         => array_map(fn($item) => $this->normalizeAnime($item), $data['data'] ?? []),
                'current_page'  => $data['pagination']['current_page'] ?? $page,
                'total_pages'   => min(50, $data['pagination']['last_visible_page'] ?? 1),
                'total_results' => $data['pagination']['items']['total'] ?? 0,
            ];
        });
    }

    /**
     * Top manga per-page.
     */
    public function topManga(int $page = 1, int $limit = 24): array
    {
        return Cache::remember("jikan_top_manga_p{$page}_l{$limit}", now()->addHours(6), function () use ($page, $limit) {
            $response = Http::timeout(15)->retry(2, 1000)
                ->get("{$this->baseUrl}/top/manga", [
                    'page'   => max(1, $page),
                    'limit'  => $limit,
                    'filter' => 'bypopularity',
                ]);

            if ($response->failed()) {
                return ['items' => [], 'current_page' => 1, 'total_pages' => 1, 'total_results' => 0];
            }

            $data = $response->json();
            return [
                'items'         => array_map(fn($item) => $this->normalizeManga($item), $data['data'] ?? []),
                'current_page'  => $data['pagination']['current_page'] ?? $page,
                'total_pages'   => min(50, $data['pagination']['last_visible_page'] ?? 1),
                'total_results' => $data['pagination']['items']['total'] ?? 0,
            ];
        });
    }

    /** Internal - normalize 1 item anime */
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

    /** Internal - normalize 1 item manga */
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
     * Cari anime + pagination
     */
    public function searchAnime(string $query, int $page = 1, int $limit = 24): array
    {
        $cacheKey = "jikan_anime_p{$page}_l{$limit}_" . md5($query);

        return Cache::remember($cacheKey, now()->addHour(), function () use ($query, $page, $limit) {
            $response = Http::timeout(15)->retry(2, 1000)
                ->get("{$this->baseUrl}/anime", [
                    'q'     => $query,
                    'page'  => max(1, $page),
                    'limit' => $limit,
                    'sfw'   => true,
                ]);

            if ($response->failed()) {
                return ['items' => [], 'current_page' => 1, 'total_pages' => 1, 'total_results' => 0];
            }

            $data = $response->json();
            return [
                'items'         => array_map(fn($item) => $this->normalizeAnime($item), $data['data'] ?? []),
                'current_page'  => $data['pagination']['current_page'] ?? $page,
                'total_pages'   => min(50, $data['pagination']['last_visible_page'] ?? 1),
                'total_results' => $data['pagination']['items']['total'] ?? 0,
            ];
        });
    }

    /**
     * Cari manga + pagination
     */
    public function searchManga(string $query, int $page = 1, int $limit = 24): array
    {
        $cacheKey = "jikan_manga_p{$page}_l{$limit}_" . md5($query);

        return Cache::remember($cacheKey, now()->addHour(), function () use ($query, $page, $limit) {
            $response = Http::timeout(15)->retry(2, 1000)
                ->get("{$this->baseUrl}/manga", [
                    'q'     => $query,
                    'page'  => max(1, $page),
                    'limit' => $limit,
                    'sfw'   => true,
                ]);

            if ($response->failed()) {
                return ['items' => [], 'current_page' => 1, 'total_pages' => 1, 'total_results' => 0];
            }

            $data = $response->json();
            return [
                'items'         => array_map(fn($item) => $this->normalizeManga($item), $data['data'] ?? []),
                'current_page'  => $data['pagination']['current_page'] ?? $page,
                'total_pages'   => min(50, $data['pagination']['last_visible_page'] ?? 1),
                'total_results' => $data['pagination']['items']['total'] ?? 0,
            ];
        });
    }
}

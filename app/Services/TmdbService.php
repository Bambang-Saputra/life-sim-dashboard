<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class TmdbService
{
    private string $apiKey;
    private string $baseUrl;
    private string $imageUrl;

    public function __construct()
    {
        // Ambil dari .env via config (bukan langsung dari env())
        $this->apiKey   = config('services.tmdb.api_key');
        $this->baseUrl  = config('services.tmdb.base_url', 'https://api.themoviedb.org/3');
        $this->imageUrl = config('services.tmdb.image_url', 'https://image.tmdb.org/t/p/w500');
    }

    /**
     * Cari film/TV berdasarkan keyword
     * Cache selama 1 jam untuk mengurangi API call
     */
    public function search(string $query, string $type = 'movie', int $page = 1): array
    {
        // Cache key unik per query + page (per-page cached)
        $cacheKey = "tmdb_search_{$type}_p{$page}_" . md5($query);

        return Cache::remember($cacheKey, now()->addHour(), function () use ($query, $type, $page) {
            try {
                $response = Http::timeout(10)
                    ->retry(2, 500)
                    ->get("{$this->baseUrl}/search/{$type}", [
                        'api_key'  => $this->apiKey,
                        'query'    => $query,
                        'language' => 'en-US',
                        'page'     => max(1, $page),
                    ]);

                $response->throw();
                $data = $response->json();
                $results = $data['results'] ?? [];

                return [
                    'items'         => array_map(fn($item) => $this->normalizeItem($item, $type), $results),
                    'current_page'  => $data['page'] ?? $page,
                    'total_pages'   => min(50, $data['total_pages'] ?? 1), // cap 50 supaya UI ga lebar
                    'total_results' => $data['total_results'] ?? count($results),
                ];

            } catch (RequestException $e) {
                Log::error('TMDB Search Error', [
                    'message' => $e->getMessage(),
                    'query'   => $query,
                    'type'    => $type,
                    'page'    => $page,
                ]);
                return ['items' => [], 'current_page' => 1, 'total_pages' => 1, 'total_results' => 0];
            }
        });
    }

    /**
     * Ambil daftar trending mingguan untuk default Library view.
     * Cache 6 jam supaya hemat API quota.
     */
    public function trending(string $type = 'movie', int $page = 1): array
    {
        $cacheKey = "tmdb_trending_{$type}_p{$page}";

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($type, $page) {
            try {
                $response = Http::timeout(10)
                    ->retry(2, 500)
                    ->get("{$this->baseUrl}/trending/{$type}/week", [
                        'api_key'  => $this->apiKey,
                        'language' => 'en-US',
                        'page'     => max(1, $page),
                    ]);

                $response->throw();
                $data = $response->json();
                $results = $data['results'] ?? [];

                return [
                    'items'         => array_map(fn($item) => $this->normalizeItem($item, $type), $results),
                    'current_page'  => $data['page'] ?? $page,
                    'total_pages'   => min(50, $data['total_pages'] ?? 1),
                    'total_results' => $data['total_results'] ?? count($results),
                ];

            } catch (RequestException $e) {
                Log::error('TMDB Trending Error', ['type' => $type, 'page' => $page, 'msg' => $e->getMessage()]);
                return ['items' => [], 'current_page' => 1, 'total_pages' => 1, 'total_results' => 0];
            }
        });
    }

    /**
     * Ambil detail lengkap satu film/TV berdasarkan ID
     */
    public function getDetail(int $id, string $type = 'movie'): ?array
    {
        $cacheKey = "tmdb_detail_{$type}_{$id}";

        return Cache::remember($cacheKey, now()->addDay(), function () use ($id, $type) {
            try {
                $response = Http::timeout(10)
                    ->get("{$this->baseUrl}/{$type}/{$id}", [
                        'api_key'            => $this->apiKey,
                        'language'           => 'en-US',
                        'append_to_response' => 'genres,credits', // Data tambahan
                    ]);

                $response->throw();
                return $this->normalizeItem($response->json(), $type);

            } catch (RequestException $e) {
                Log::error('TMDB Detail Error', ['id' => $id, 'type' => $type]);
                return null;
            }
        });
    }

    /**
     * Normalize data dari API agar format-nya konsisten
     * Ini penting karena format film dan TV series berbeda di TMDB
     */
    private function normalizeItem(array $item, string $type): array
    {
        return [
            'external_id'  => $item['id'],
            'api_type'     => $type,
            'title'        => $item['title'] ?? $item['name'] ?? 'Unknown', // film vs TV
            'cover_image'  => $item['poster_path']
                ? "{$this->imageUrl}{$item['poster_path']}"
                : null,
            'genre'        => implode(', ', array_column($item['genres'] ?? [], 'name')),
            'release_year' => substr($item['release_date'] ?? $item['first_air_date'] ?? '', 0, 4),
            'overview'     => $item['overview'] ?? '',
            'tmdb_rating'  => $item['vote_average'] ?? 0,
            // Simpan raw data untuk metadata
            'metadata'     => [
                'vote_count'    => $item['vote_count'] ?? 0,
                'popularity'    => $item['popularity'] ?? 0,
                'original_lang' => $item['original_language'] ?? 'en',
            ],
        ];
    }
}

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
    public function search(string $query, string $type = 'movie'): array
    {
        // Cache key unik berdasarkan parameter
        $cacheKey = "tmdb_search_{$type}_" . md5($query);

        return Cache::remember($cacheKey, now()->addHour(), function () use ($query, $type) {
            try {
                $response = Http::timeout(10)         // Timeout 10 detik
                    ->retry(2, 500)                    // Retry 2x jika gagal, jeda 500ms
                    ->get("{$this->baseUrl}/search/{$type}", [
                        'api_key'  => $this->apiKey,
                        'query'    => $query,
                        'language' => 'en-US',
                        'page'     => 1,
                    ]);

                // throw() otomatis lempar exception jika status 4xx/5xx
                $response->throw();

                $results = $response->json('results', []);

                // Normalize data agar konsisten antara film dan TV
                return array_map(fn($item) => $this->normalizeItem($item, $type), $results);

            } catch (RequestException $e) {
                // Log error, return array kosong agar UI tidak crash
                Log::error('TMDB API Error', [
                    'message' => $e->getMessage(),
                    'query'   => $query,
                    'type'    => $type,
                ]);

                return [];
            }
        });
    }

    /**
     * Ambil daftar trending mingguan untuk default Library view.
     * Cache 6 jam supaya hemat API quota.
     */
    public function trending(string $type = 'movie', int $limit = 12): array
    {
        $cacheKey = "tmdb_trending_{$type}_{$limit}";

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($type, $limit) {
            try {
                $response = Http::timeout(10)
                    ->retry(2, 500)
                    ->get("{$this->baseUrl}/trending/{$type}/week", [
                        'api_key'  => $this->apiKey,
                        'language' => 'en-US',
                    ]);

                $response->throw();
                $results = $response->json('results', []);

                return array_map(
                    fn($item) => $this->normalizeItem($item, $type),
                    array_slice($results, 0, $limit)
                );

            } catch (RequestException $e) {
                Log::error('TMDB Trending Error', ['type' => $type, 'msg' => $e->getMessage()]);
                return [];
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

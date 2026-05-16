<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_items', function (Blueprint $table) {
            $table->id();

            // ── PERSONAL APP: tidak ada user_id ──

            // Sumber data: dari TMDB (film/tv) atau Jikan (anime/manga)
            $table->enum('api_type', ['movie', 'tv', 'anime', 'manga']);

            // ID dari API eksternal (untuk re-fetch data jika diperlukan)
            $table->unsignedBigInteger('external_id');

            $table->string('title');
            $table->string('cover_image')->nullable();  // URL gambar dari API
            $table->string('genre')->nullable();

            // Rating personal (1-10, step 0.5)
            $table->decimal('personal_rating', 3, 1)->nullable();

            $table->text('personal_review')->nullable();

            // Status baca/tonton
            $table->enum('status', ['plan_to', 'ongoing', 'completed', 'dropped'])
                  ->default('plan_to');

            // Simpan metadata tambahan dari API (year, episodes, studio, dll)
            // Menggunakan JSON agar fleksibel tanpa perlu tambah kolom
            $table->json('metadata')->nullable();

            $table->timestamps();

            // ── PERSONAL: unique hanya per (api_type, external_id) ──
            // Tidak ada user_id di constraint ini.
            // Satu judul dari satu sumber tidak bisa disimpan dua kali.
            $table->unique(['api_type', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_items');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quests', function (Blueprint $table) {
            $table->id();

            // ── PERSONAL APP: tidak ada user_id sama sekali ──
            // Semua data di tabel ini adalah milik pemilik tunggal aplikasi.

            $table->string('title');                    // "Siram tanaman" dsb
            $table->text('description')->nullable();
            $table->boolean('is_completed')->default(false);

            // Enum untuk prioritas quest (layaknya difficulty di game)
            $table->enum('priority', ['easy', 'normal', 'hard', 'legendary'])
                  ->default('normal');

            // Kategori quest bebas (farming, social, crafting, dll)
            $table->string('category')->nullable();

            // Batas waktu quest selesai
            $table->timestamp('due_at')->nullable();

            // Waktu alarm notifikasi (bisa berbeda dari due_at)
            // Contoh: due_at = 17:00, alarm_at = 16:30 (30 menit sebelum)
            $table->timestamp('alarm_at')->nullable();

            // XP reward saat quest selesai (gamification)
            $table->unsignedSmallInteger('xp_reward')->default(10);

            // Waktu aktual penyelesaian
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            // Index untuk query yang sering: filter by status & due date
            $table->index('is_completed');
            $table->index('due_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quests');
    }
};

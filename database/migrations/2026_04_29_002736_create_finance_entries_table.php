<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_entries', function (Blueprint $table) {
            $table->id();

            // ── PERSONAL APP: tidak ada user_id ──

            // "in" = pemasukan (Gold earned), "out" = pengeluaran (Gold spent)
            $table->enum('type', ['in', 'out']);

            // Gunakan decimal untuk menghindari floating point error di kalkulasi
            $table->decimal('amount', 15, 2);

            // Kategori pengeluaran/pemasukan (salary, food, entertainment, dll)
            $table->string('category');

            $table->string('description')->nullable();

            // Tanggal transaksi (bukan created_at, agar bisa input tanggal mundur)
            $table->date('recorded_at');

            $table->timestamps();

            // Index untuk query laporan bulanan: filter by tanggal & tipe
            $table->index('recorded_at');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_entries');
    }
};

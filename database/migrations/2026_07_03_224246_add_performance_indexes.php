<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Index untuk pola query terpanas aplikasi:
 * - finance_entries: agregasi bulanan per type + rentang tanggal + kategori
 * - quests: daftar pending/overdue + completed hari ini
 * - library_items: filter tipe & status koleksi
 * - saving_deposits: rekap setoran per bulan
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('finance_entries', function (Blueprint $table) {
            $table->index(['type', 'recorded_at'], 'fe_type_recorded_idx');
            $table->index('recorded_at', 'fe_recorded_idx');
            $table->index('category', 'fe_category_idx');
        });

        Schema::table('quests', function (Blueprint $table) {
            $table->index(['is_completed', 'due_at'], 'q_completed_due_idx');
            $table->index('completed_at', 'q_completed_at_idx');
        });

        Schema::table('library_items', function (Blueprint $table) {
            $table->index(['api_type', 'status'], 'li_type_status_idx');
        });

        Schema::table('saving_deposits', function (Blueprint $table) {
            $table->index('deposited_at', 'sd_deposited_idx');
        });
    }

    public function down(): void
    {
        Schema::table('finance_entries', function (Blueprint $table) {
            $table->dropIndex('fe_type_recorded_idx');
            $table->dropIndex('fe_recorded_idx');
            $table->dropIndex('fe_category_idx');
        });

        Schema::table('quests', function (Blueprint $table) {
            $table->dropIndex('q_completed_due_idx');
            $table->dropIndex('q_completed_at_idx');
        });

        Schema::table('library_items', function (Blueprint $table) {
            $table->dropIndex('li_type_status_idx');
        });

        Schema::table('saving_deposits', function (Blueprint $table) {
            $table->dropIndex('sd_deposited_idx');
        });
    }
};

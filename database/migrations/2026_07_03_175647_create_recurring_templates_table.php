<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recurring_templates', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['in', 'out']);
            $table->decimal('amount', 15, 2);
            $table->string('category', 100);
            $table->string('description')->nullable();
            $table->unsignedTinyInteger('day_of_month'); // 1-31, di-clamp ke hari terakhir bulan pendek
            $table->boolean('is_active')->default(true);
            $table->char('last_posted_period', 7)->nullable(); // 'YYYY-MM' — kunci idempotensi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recurring_templates');
    }
};

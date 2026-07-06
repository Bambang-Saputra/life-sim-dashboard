<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recurring_quests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category', 50)->nullable();
            $table->enum('priority', ['easy', 'normal', 'hard', 'legendary'])->default('normal');
            $table->boolean('is_active')->default(true);
            $table->date('last_spawned_date')->nullable(); // kunci idempotensi harian
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_quests');
    }
};

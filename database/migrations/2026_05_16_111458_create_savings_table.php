<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('savings', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Tabungan Umum", "Liburan Bali"
            $table->decimal('target_amount', 15, 2)->nullable();
            $table->date('target_date')->nullable();
            $table->string('icon', 8)->default('💰');
            $table->string('color', 16)->default('grass'); // grass / corn / sky / berry
            $table->text('note')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('savings');
    }
};

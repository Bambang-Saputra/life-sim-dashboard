<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quests', function (Blueprint $table) {
            $table->boolean('is_important')->default(false)->after('is_completed');
            $table->text('history')->nullable()->after('description');
            $table->integer('progress')->default(0)->after('xp_reward'); // 0-100
        });
    }

    public function down(): void
    {
        Schema::table('quests', function (Blueprint $table) {
            $table->dropColumn(['is_important', 'history', 'progress']);
        });
    }
};

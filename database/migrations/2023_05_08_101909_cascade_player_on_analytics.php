<?php

declare(strict_types=1);

use App\Models\Player;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('analytics', function (Blueprint $table) {
            $table->dropForeignIdFor(Player::class);
        });

        Schema::table('medal_analytics', function (Blueprint $table) {
            $table->dropForeignIdFor(Player::class);
        });

        Schema::table('analytics', function (Blueprint $table) {
            $table
                ->foreign('player_id')
                ->references('id')
                ->on('players')
                ->cascadeOnDelete();
        });

        Schema::table('medal_analytics', function (Blueprint $table) {
            $table
                ->foreign('player_id')
                ->references('id')
                ->on('players')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        // Internationally blank
    }
};

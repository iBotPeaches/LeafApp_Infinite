<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_players', function (Blueprint $table) {
            $table
                ->tinyInteger('pre_champion_rank')
                ->unsigned()
                ->after('matches_remaining')
                ->nullable();
            $table
                ->tinyInteger('post_champion_rank')
                ->unsigned()
                ->after('matches_remaining')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('game_players', function (Blueprint $table) {
            $table->dropColumn('pre_champion_rank');
            $table->dropColumn('post_champion_rank');
        });
    }
};

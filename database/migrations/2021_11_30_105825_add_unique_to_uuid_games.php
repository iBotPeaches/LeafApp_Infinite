<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddUniqueToUuidGames extends Migration
{
    public function up(): void
    {
        Schema::table('game_players', function (Blueprint $table) {
            $table->dropForeign(['game_id']);

            $table
                ->foreign('game_id')
                ->references('id')
                ->on('games')
                ->cascadeOnDelete();
        });

        DB::table('games')
            ->select('uuid', DB::raw('count(`uuid`) as occurrences'))
            ->groupBy('uuid')
            ->orderByDesc('id')
            ->having('occurrences', '>', 1)
            ->each(function (object $game) {
                DB::table('games')
                    ->where('uuid', $game->uuid)
                    ->delete();
            });

        Schema::table('games', function (Blueprint $table) {
            $table->unique('uuid');
        });
    }

    public function down(): void
    {
        Schema::table('uuid_games', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
        });
    }
}

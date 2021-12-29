<?php

use App\Models\GameTeam;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInternalTeamIdToGamePlayers extends Migration
{
    public function up(): void
    {
        Schema::table('game_players', function (Blueprint $table) {
            $table->foreignIdFor(GameTeam::class)
                ->nullable(true)
                ->after('player_id')
                ->constrained();
        });
    }

    public function down(): void
    {
        Schema::table('game_players', function (Blueprint $table) {
            $table->dropConstrainedForeignId('game_team_id');
        });
    }
}

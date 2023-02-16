<?php

use App\Models\Game;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastGameIdToPlayers extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table
                ->foreignIdFor(Game::class, 'last_game_id_pulled')
                ->after('is_private')
                ->nullable(true)
                ->constrained('games', 'id');
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropConstrainedForeignId('last_game_id_pulled');
        });
    }
}

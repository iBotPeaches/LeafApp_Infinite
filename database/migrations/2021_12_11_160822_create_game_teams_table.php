<?php

use App\Models\Game;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameTeamsTable extends Migration
{
    public function up(): void
    {
        Schema::create('game_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Game::class)->constrained();
            $table->unsignedTinyInteger('internal_team_id');
            $table->string('name', 16);
            $table->string('emblem_url');
            $table->unsignedTinyInteger('outcome');
            $table->unsignedTinyInteger('rank');
            $table->mediumInteger('score');

            $table->unique([
                'game_id',
                'internal_team_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_teams');
    }
}

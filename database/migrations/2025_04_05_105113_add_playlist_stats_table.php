<?php

declare(strict_types=1);

use App\Models\Game;
use App\Models\Player;
use App\Models\Playlist;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('playlist_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Playlist::class);
            $table->integer('total_matches')->unsigned();
            $table->integer('total_players')->unsigned();
            $table->integer('total_unique_players')->unsigned();
            $table->unique(['playlist_id']);
        });

        Schema::create('playlist_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Playlist::class);
            $table->foreignIdFor(Game::class);
            $table->foreignIdFor(Player::class);
            $table->string('key', 32);
            $table->tinyInteger('place')->unsigned();
            $table->double('value');
            $table->string('label', 64);
            $table->timestamps();
            $table->unique(['playlist_id', 'game_id', 'player_id', 'key'], 'playlist_analytics_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('playlist_analytics');
        Schema::dropIfExists('playlist_stats');
    }
};

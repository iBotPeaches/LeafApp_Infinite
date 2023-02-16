<?php

use App\Models\Category;
use App\Models\Game;
use App\Models\Map;
use App\Models\Player;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMatchesTable extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignIdFor(Category::class)->constrained();
            $table->foreignIdFor(Map::class)->constrained();

            $table->boolean('is_ffa');
            $table->boolean('is_scored');

            $table->tinyInteger('experience');

            $table->dateTime('occurred_at');
            $table->integer('duration_seconds');
        });

        Schema::create('game_players', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Player::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Game::class)->constrained()->cascadeOnDelete();

            $table->unsignedTinyInteger('rank');
            $table->unsignedSmallInteger('outcome');
            $table->double('kd', 8, 4);
            $table->double('kda', 8, 4);
            $table->mediumInteger('score')->nullable(true);

            $table->unsignedSmallInteger('kills');
            $table->unsignedSmallInteger('deaths');
            $table->unsignedSmallInteger('assists');
            $table->unsignedSmallInteger('betrayals');
            $table->unsignedSmallInteger('suicides');
            $table->unsignedSmallInteger('vehicle_destroys')->nullable(true);
            $table->unsignedSmallInteger('vehicle_hijacks')->nullable(true);
            $table->unsignedSmallInteger('medal_count');
            $table->unsignedMediumInteger('damage_taken');
            $table->unsignedMediumInteger('damage_dealt');
            $table->unsignedSmallInteger('shots_fired');
            $table->unsignedSmallInteger('shots_landed');
            $table->unsignedSmallInteger('shots_missed');

            $table->double('accuracy', 5, 2);
            $table->unsignedSmallInteger('rounds_won')->nullable(true);
            $table->unsignedSmallInteger('rounds_lost')->nullable(true);
            $table->unsignedSmallInteger('rounds_tied')->nullable(true);

            $table->unsignedSmallInteger('kills_melee')->nullable(true);
            $table->unsignedSmallInteger('kills_grenade')->nullable(true);
            $table->unsignedSmallInteger('kills_headshot')->nullable(true);
            $table->unsignedSmallInteger('kills_power')->nullable(true);

            $table->unsignedSmallInteger('assists_emp')->nullable(true);
            $table->unsignedSmallInteger('assists_driver')->nullable(true);
            $table->unsignedSmallInteger('assists_callout')->nullable(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_players');
        Schema::dropIfExists('matches');
    }
}

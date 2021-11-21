<?php

use App\Models\Category;
use App\Models\Map;
use App\Models\Match;
use App\Models\Player;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMatchesTable extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignIdFor(Category::class);
            $table->foreignIdFor(Map::class);

            $table->boolean('is_ffa');
            $table->boolean('is_scored');

            $table->tinyInteger('experience');

            $table->dateTime('occurred_at');
            $table->integer('duration_seconds');
        });

        Schema::create('match_players', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Player::class);
            $table->foreignIdFor(Match::class);

            $table->unsignedTinyInteger('rank');
            $table->unsignedSmallInteger('outcome');
            $table->double('kd', 8, 4);
            $table->double('kda', 8, 4);
            $table->unsignedMediumInteger('score');

            $table->unsignedSmallInteger('kills');
            $table->unsignedSmallInteger('deaths');
            $table->unsignedSmallInteger('assists');
            $table->unsignedSmallInteger('betrayals');
            $table->unsignedSmallInteger('suicides');
            $table->unsignedSmallInteger('vehicle_destroys');
            $table->unsignedSmallInteger('vehicle_hijacks');
            $table->unsignedSmallInteger('medal_count');
            $table->unsignedSmallInteger('damage_taken');
            $table->unsignedSmallInteger('damage_dealt');
            $table->unsignedSmallInteger('shots_fired');
            $table->unsignedSmallInteger('shots_landed');
            $table->unsignedSmallInteger('shots_missed');

            $table->double('accuracy', 5, 2);
            $table->unsignedSmallInteger('rounds_won');
            $table->unsignedSmallInteger('rounds_lost');
            $table->unsignedSmallInteger('rounds_tied');

            $table->unsignedSmallInteger('kills_melee');
            $table->unsignedSmallInteger('kills_grenade');
            $table->unsignedSmallInteger('kills_headshot');
            $table->unsignedSmallInteger('kills_power');

            $table->unsignedSmallInteger('assists_emp');
            $table->unsignedSmallInteger('assists_driver');
            $table->unsignedSmallInteger('assists_callout');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_players');
        Schema::dropIfExists('matches');
    }
}

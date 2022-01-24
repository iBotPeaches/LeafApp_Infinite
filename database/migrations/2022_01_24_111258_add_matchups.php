<?php
declare(strict_types=1);

use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Championship;
use App\Models\Matchup;

class AddMatchups extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->uuid('faceit_id')->after('xuid')->nullable(true);
        });

        Schema::create('matchups', function (Blueprint $table) {
            $table->id();
            $table->uuid('faceit_id');
            $table->foreignIdFor(Championship::class)->constrained()->cascadeOnDelete();
            $table->tinyInteger('round');
            $table->tinyInteger('group');
            $table->tinyInteger('best_of');
            $table->dateTime('started_at');
            $table->dateTime('ended_at');
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Matchup::class)->constrained()->cascadeOnDelete();
            $table->uuid('faceit_id');
            $table->string('name', 64);
            $table->tinyInteger('points')->default(0);
            $table->tinyInteger('outcome');
        });

        Schema::create('team_players', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Player::class)->nullable(true)->constrained()->nullOnDelete();
            $table->string('faceit_name', 64);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_players');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('matchups');

        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('faceit_id');
        });
    }
}

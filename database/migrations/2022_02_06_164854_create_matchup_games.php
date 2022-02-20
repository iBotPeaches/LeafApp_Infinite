<?php
declare(strict_types=1);

use App\Models\Game;
use App\Models\Matchup;
use App\Models\MatchupTeam;
use App\Models\Player;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchupGames extends Migration
{
    public function up(): void
    {
        Schema::create('matchup_game', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Matchup::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Game::class)->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matchup_game');
    }
}

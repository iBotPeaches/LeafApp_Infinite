<?php

declare(strict_types=1);

use App\Models\GameTeam;
use App\Models\Team;
use App\Services\Autocode\InfiniteInterface;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (App::isProduction()) {
            /** @var InfiniteInterface $client */
            $client = resolve(InfiniteInterface::class);
            $client->metadataTeams();
        }

        Schema::table('game_teams', function (Blueprint $table) {
            $table->foreignIdFor(Team::class)
                ->after('game_id')
                ->nullable(true)
                ->constrained();
        });

        $teams = Team::query()
            ->get()
            ->keyBy('internal_id');

        GameTeam::query()->cursor()->each(function (GameTeam $gameTeam) use ($teams) {
            $gameTeam->team()->associate($teams->get($gameTeam->internal_team_id));
            $gameTeam->saveOrFail();
        });
    }

    public function down(): void
    {
        Schema::table('game_teams', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(Team::class);
        });
    }
};

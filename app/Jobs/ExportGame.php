<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Game;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExportGame implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public static array $header = [
        'Date',
        'SeasonNumber',
        'SeasonVersion',
        'Player',
        'MatchId',
        'Map',
        'Category',
        'Playlist',
        'Input',
        'Queue',
        'PreCsr',
        'PostCsr',
        'Mmr',
        'Outcome',
        'Accuracy',
        'DamageDone',
        'DamageTaken',
        'ShotsFired',
        'ShotsLanded',
        'ShotsMissed',
        'KD',
        'KDA',
        'Kills',
        'Deaths',
        'Assists',
        'Betrayals',
        'Suicides',
        'ExpectedKills',
        'ExpectedDeaths',
        'Score',
        'Perfects',
        'Medals',
        'TeamOutcome',
        'TeamRank',
        'TeamScore',
        'TeamMMR',
        'TeamCSR',
        'LengthSeconds',
    ];

    protected array $data = [];

    public Game $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function handle(): array
    {
        $this->game->load('players.team.players');

        foreach ($this->game->players->sortBy('outcome') as $gamePlayer) {
            $perfectMedal = $gamePlayer->hydrated_medals->firstWhere('name', 'Perfect');
            $team = $gamePlayer->team;

            $this->data[] = [
                $this->game->occurred_at->toIso8601ZuluString(),
                $this->game->season_number,
                $this->game->season_version,
                $gamePlayer->player->gamertag,
                $this->game->uuid,
                $this->game->map->name,
                $this->game->gamevariant->name ?? $this->game->category?->name,
                $this->game->playlist?->name,
                $this->game->playlist?->input?->description,
                $this->game->playlist?->queue?->description,
                $gamePlayer->pre_csr,
                $gamePlayer->post_csr,
                $gamePlayer->mmr,
                $gamePlayer->outcome->description,
                $gamePlayer->accuracy,
                $gamePlayer->damage_dealt,
                $gamePlayer->damage_taken,
                $gamePlayer->shots_fired,
                $gamePlayer->shots_landed,
                $gamePlayer->shots_missed,
                $gamePlayer->kd,
                $gamePlayer->kda,
                $gamePlayer->kills,
                $gamePlayer->deaths,
                $gamePlayer->assists,
                $gamePlayer->betrayals,
                $gamePlayer->suicides,
                $gamePlayer->expected_kills,
                $gamePlayer->expected_deaths,
                $gamePlayer->getRawOriginal('score'),
                $perfectMedal->count ?? 0,
                $gamePlayer->medal_count,
                $team?->outcome?->description,
                $team?->rank,
                $team?->score,
                $team?->mmr,
                $team?->csr,
                $this->game->duration_seconds,
            ];
        }

        return $this->data;
    }
}

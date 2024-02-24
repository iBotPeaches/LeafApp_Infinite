<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Scrim;
use App\Support\Scrim\ScrimDecorator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExportScrimPlayers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public static array $header = [
        'Player',
        'Kills',
        'Deaths',
        'Assists',
        'KD',
        'KDA',
        'Accuracy',
        'DamageDone',
        'DamageTaken',
        'AvgScore',
        'AvgRank',
    ];

    protected array $mergedStats = [];

    protected array $data = [];

    public function __construct(public Scrim $scrim)
    {
        $scrimDecorator = new ScrimDecorator($this->scrim);
        $this->mergedStats = $scrimDecorator->mergedStats;
    }

    public function handle(): array
    {
        foreach ($this->mergedStats as $mergedStat) {
            $this->data[] = [
                'player' => $mergedStat->player->gamertag,
                'kills' => $mergedStat->kills,
                'deaths' => $mergedStat->deaths,
                'assists' => $mergedStat->assists,
                'kd' => $mergedStat->kd,
                'kda' => $mergedStat->kda,
                'accuracy' => $mergedStat->accuracy,
                'damageDone' => $mergedStat->damage_dealt,
                'damageTaken' => $mergedStat->damage_taken,
                'avgScore' => $mergedStat->score,
                'avgRank' => $mergedStat->rank,
            ];
        }

        return $this->data;
    }
}

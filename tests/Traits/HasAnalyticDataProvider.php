<?php
declare(strict_types = 1);

namespace Tests\Traits;

use App\Support\Analytics\Stats\BestAccuracyServiceRecord;
use App\Support\Analytics\Stats\BestKDAServiceRecord;
use App\Support\Analytics\Stats\BestKDServiceRecord;
use App\Support\Analytics\Stats\HighestScoreInRankedGame;
use App\Support\Analytics\Stats\LongestMatchmakingGame;
use App\Support\Analytics\Stats\MostAssistsInGame;
use App\Support\Analytics\Stats\MostBetrayalsServiceRecord;
use App\Support\Analytics\Stats\MostDeathsInGame;
use App\Support\Analytics\Stats\MostKillsInGame;
use App\Support\Analytics\Stats\MostKillsInRankedGame;
use App\Support\Analytics\Stats\MostKillsServiceRecord;
use App\Support\Analytics\Stats\MostMedalsInGame;
use App\Support\Analytics\Stats\MostMedalsServiceRecord;
use App\Support\Analytics\Stats\MostTimePlayedServiceRecord;

trait HasAnalyticDataProvider
{
    public function analyticDataProvider(): array
    {
        return [
            [
                new BestAccuracyServiceRecord(),
            ],
            [
                new BestKDAServiceRecord(),
            ],
            [
                new BestKDServiceRecord(),
            ],
            [
                new MostBetrayalsServiceRecord(),
            ],
            [
                new MostKillsInRankedGame(),
            ],
            [
                new MostKillsServiceRecord(),
            ],
            [
                new MostMedalsServiceRecord(),
            ],
            [
                new MostTimePlayedServiceRecord(),
            ],
            [
                new LongestMatchmakingGame(),
            ],
            [
                new HighestScoreInRankedGame(),
            ],
            [
                new MostKillsInGame(),
            ],
            [
                new MostDeathsInGame(),
            ],
            [
                new MostAssistsInGame(),
            ],
            [
                new MostMedalsInGame(),
            ]
        ];
    }
}

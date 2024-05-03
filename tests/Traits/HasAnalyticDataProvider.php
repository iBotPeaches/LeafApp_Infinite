<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Support\Analytics\Stats\BestAccuracyServiceRecord;
use App\Support\Analytics\Stats\BestKDAServiceRecord;
use App\Support\Analytics\Stats\BestKDServiceRecord;
use App\Support\Analytics\Stats\HighestScoreInRankedGame;
use App\Support\Analytics\Stats\HighestScoreInUnrankedGame;
use App\Support\Analytics\Stats\LongestMatchmakingGame;
use App\Support\Analytics\Stats\MostAssistsInGame;
use App\Support\Analytics\Stats\MostBetrayalsServiceRecord;
use App\Support\Analytics\Stats\MostDeathsInGame;
use App\Support\Analytics\Stats\MostGamesPlayedServiceRecord;
use App\Support\Analytics\Stats\MostKillsInGame;
use App\Support\Analytics\Stats\MostKillsInRankedGame;
use App\Support\Analytics\Stats\MostKillsServiceRecord;
use App\Support\Analytics\Stats\MostKillsWithZeroDeathsGame;
use App\Support\Analytics\Stats\MostMedalsInGame;
use App\Support\Analytics\Stats\MostMedalsServiceRecord;
use App\Support\Analytics\Stats\MostPerfectsInRankedGame;
use App\Support\Analytics\Stats\MostQuitMap;
use App\Support\Analytics\Stats\MostScoreServiceRecord;
use App\Support\Analytics\Stats\MostTimePlayedServiceRecord;
use App\Support\Analytics\Stats\MostXpPlayer;

trait HasAnalyticDataProvider
{
    public static function analyticDataProvider(): array
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
                new MostKillsWithZeroDeathsGame(),
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
                new HighestScoreInUnrankedGame(),
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
            ],
            [
                new MostQuitMap(),
            ],
            [
                new MostXpPlayer(),
            ],
            [
                new MostScoreServiceRecord(),
            ],
            [
                new MostGamesPlayedServiceRecord(),
            ],
            [
                new MostPerfectsInRankedGame(),
            ],
        ];
    }
}

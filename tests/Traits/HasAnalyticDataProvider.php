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
use App\Support\Analytics\Stats\MostCalloutAssistsServiceRecord;
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

trait HasAnalyticDataProvider
{
    public static function analyticDataProvider(): array
    {
        return [
            'best accuracy - sr' => [
                new BestAccuracyServiceRecord,
            ],
            'best kda - sr' => [
                new BestKDAServiceRecord,
            ],
            'best kd - sr' => [
                new BestKDServiceRecord,
            ],
            'most betrayals - sr' => [
                new MostBetrayalsServiceRecord,
            ],
            'most kills in ranked game' => [
                new MostKillsInRankedGame,
            ],
            'most kills w/ 0 deaths game' => [
                new MostKillsWithZeroDeathsGame,
            ],
            'most kills - sr' => [
                new MostKillsServiceRecord,
            ],
            'most medals - sr' => [
                new MostMedalsServiceRecord,
            ],
            'most time played - sr' => [
                new MostTimePlayedServiceRecord,
            ],
            'longest matchmaking game' => [
                new LongestMatchmakingGame,
            ],
            'highest score in ranked game' => [
                new HighestScoreInRankedGame,
            ],
            'highest score in unranked game' => [
                new HighestScoreInUnrankedGame,
            ],
            'most kills in game' => [
                new MostKillsInGame,
            ],
            'most deaths in game' => [
                new MostDeathsInGame,
            ],
            'most assists in game' => [
                new MostAssistsInGame,
            ],
            'most medals in game' => [
                new MostMedalsInGame,
            ],
            'most quit map' => [
                new MostQuitMap,
            ],
            'most score - sr' => [
                new MostScoreServiceRecord,
            ],
            'most games played - sr' => [
                new MostGamesPlayedServiceRecord,
            ],
            'most perfects in ranked game' => [
                new MostPerfectsInRankedGame,
            ],
            'most callouts - sr' => [
                new MostCalloutAssistsServiceRecord,
            ],
        ];
    }
}

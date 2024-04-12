<?php

namespace App\Jobs;

use App\Enums\AnalyticType;
use App\Enums\QueueName;
use App\Models\Analytic;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Map;
use App\Models\Player;
use App\Models\ServiceRecord;
use App\Support\Analytics\AnalyticInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class ProcessAnalytic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public AnalyticInterface $analytic
    ) {
        $this->onQueue(QueueName::RECORDS);
    }

    public function handle(): void
    {
        DB::transaction(function () {
            Analytic::purgeKey($this->analytic->key());

            $results = $this->analytic->results(1000);

            $topTen = $results?->slice(0, 10);
            $topHundred = $results?->slice(0, 100);
            $topThousand = $results?->slice(0, 1000);

            switch ($this->analytic->type()) {
                case AnalyticType::PLAYER():
                    $this->handleServiceRecordResults($topHundred);
                    break;
                case AnalyticType::GAME():
                    $this->handleGamePlayerResults($topHundred);
                    break;
                case AnalyticType::ONLY_GAME():
                    $this->handleGameResults($topHundred);
                    break;
                case AnalyticType::MAP():
                    $this->handleMapResults($topHundred);
                    break;
                case AnalyticType::ONLY_PLAYER():
                    $this->handleOnlyPlayerResults($topHundred);
                    break;
            }

            foreach ([$topTen, $topHundred, $topThousand] as $resultSet) {
                $writer = Writer::createFromString();
                $writer->insertOne($this->analytic->csvHeader());
                $writer->insertAll($this->analytic->csvData($resultSet));

                $slug = $this->analytic->slug(count($resultSet ?? []));

                Storage::disk('public')->put('top-ten/'.$slug.'.csv', $writer->toString());
            }
        });
    }

    /** @param  Collection<int, Game>|null  $games */
    private function handleGameResults(?Collection $games): void
    {
        $games?->each(function (Game $game, int $index) {
            $analytic = new Analytic();
            $analytic->key = $this->analytic->key();
            $analytic->place = $index + 1;
            $analytic->value = (float) $game->{$this->analytic->property()};
            $analytic->game()->associate($game);
            $analytic->save();
        });
    }

    /** @param  Collection<int, GamePlayer>|null  $gamePlayers */
    private function handleGamePlayerResults(?Collection $gamePlayers): void
    {
        $lastIndex = null;
        $lastValue = null;

        $gamePlayers?->each(function (GamePlayer $gamePlayer, int $index) use (&$lastIndex, &$lastValue) {
            $value = (float) $gamePlayer->{$this->analytic->property()};
            $place = $index + 1;
            if ($value === $lastValue) {
                $place = $lastIndex;
            }

            $analytic = new Analytic();
            $analytic->key = $this->analytic->key();
            $analytic->place = $place;
            $analytic->value = $value;
            $analytic->game()->associate($gamePlayer->game);
            $analytic->player()->associate($gamePlayer->player);
            $analytic->save();

            // Store our last values in case users share the value.
            $lastIndex = $analytic->place;
            $lastValue = $analytic->value;
        });
    }

    /** @param  Collection<int, ServiceRecord>|null  $serviceRecords */
    private function handleServiceRecordResults(?Collection $serviceRecords): void
    {
        $lastIndex = null;
        $lastValue = null;

        $serviceRecords?->each(function (ServiceRecord $serviceRecord, int $index) use (&$lastIndex, &$lastValue) {
            $value = (float) $serviceRecord->{$this->analytic->property()};
            $place = $index + 1;
            if ($value === $lastValue) {
                $place = $lastIndex;
            }

            $analytic = new Analytic();
            $analytic->key = $this->analytic->key();
            $analytic->place = $place;
            $analytic->value = $value;
            $analytic->player()->associate($serviceRecord->player);
            $analytic->save();

            // Store our last values in case users share the value.
            $lastIndex = $analytic->place;
            $lastValue = $analytic->value;
        });
    }

    /** @param  Collection<int, Map>|null  $maps */
    private function handleMapResults(?Collection $maps): void
    {
        $maps?->each(function (Map $map, int $index) {
            $analytic = new Analytic();
            $analytic->key = $this->analytic->key();
            $analytic->place = $index + 1;
            $analytic->value = (float) $map->{$this->analytic->property()};
            $analytic->map()->associate($map);
            $analytic->save();
        });
    }

    /** @param  Collection<int, Player>|null  $players */
    private function handleOnlyPlayerResults(?Collection $players): void
    {
        $lastIndex = null;
        $lastValue = null;

        $players?->each(function (Player $player, int $index) use (&$lastIndex, &$lastValue) {
            $value = (float) $player->{$this->analytic->property()};
            $place = $index + 1;
            if ($value === $lastValue) {
                $place = $lastIndex;
            }

            $analytic = new Analytic();
            $analytic->key = $this->analytic->key();
            $analytic->place = $place;
            $analytic->value = $value;
            $analytic->player()->associate($player);
            $analytic->saveOrFail();

            // Store our last values in case users share the value.
            $lastIndex = $analytic->place;
            $lastValue = $analytic->value;
        });
    }
}

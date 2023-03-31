<?php

namespace App\Jobs;

use App\Enums\AnalyticType;
use App\Enums\QueueName;
use App\Models\Analytic;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Map;
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
                    $this->handleServiceRecordResults($topTen);
                    break;
                case AnalyticType::GAME():
                    $this->handleGamePlayerResults($topTen);
                    break;
                case AnalyticType::ONLY_GAME():
                    $this->handleGameResults($topTen);
                    break;
                case AnalyticType::MAP():
                    $this->handleMapResults($topTen);
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

    private function handleGameResults(?Collection $games): void
    {
        $games?->each(function (Game $game) {
            $analytic = new Analytic();
            $analytic->key = $this->analytic->key();
            $analytic->value = (float) $game->{$this->analytic->property()};
            $analytic->game()->associate($game);
            $analytic->saveOrFail();
        });
    }

    private function handleGamePlayerResults(?Collection $gamePlayers): void
    {
        $gamePlayers?->each(function (GamePlayer $gamePlayer) {
            $analytic = new Analytic();
            $analytic->key = $this->analytic->key();
            $analytic->value = (float) $gamePlayer->{$this->analytic->property()};
            $analytic->game()->associate($gamePlayer->game);
            $analytic->player()->associate($gamePlayer->player);
            $analytic->saveOrFail();
        });
    }

    private function handleServiceRecordResults(?Collection $serviceRecords): void
    {
        $serviceRecords?->each(function (ServiceRecord $serviceRecord) {
            $analytic = new Analytic();
            $analytic->key = $this->analytic->key();
            $analytic->value = (float) $serviceRecord->{$this->analytic->property()};
            $analytic->player()->associate($serviceRecord->player);
            $analytic->saveOrFail();
        });
    }

    private function handleMapResults(?Collection $maps): void
    {
        $maps?->each(function (Map $map) {
            $analytic = new Analytic();
            $analytic->key = $this->analytic->key();
            $analytic->value = (float) $map->{$this->analytic->property()};
            $analytic->map()->associate($map);
            $analytic->saveOrFail();
        });
    }
}

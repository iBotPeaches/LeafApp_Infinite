<?php

namespace App\Jobs;

use App\Enums\Mode;
use App\Enums\QueueName;
use App\Models\Medal;
use App\Models\MedalAnalytic;
use App\Models\Season;
use App\Models\ServiceRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Csv\Writer;

class ProcessMedalAnalytic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Medal $medal,
        public ?Season $season = null,
    ) {
        $this->onQueue(QueueName::RECORDS);
    }

    public function handle(): void
    {
        DB::transaction(function () {
            MedalAnalytic::purgeSeason($this->medal, $this->season);

            /** @var Mode $mode */
            foreach (Mode::serviceRecordModes() as $mode) {
                $results = $this->obtainResults($mode);

                $seasonName = $this->season?->name ?? 'All Seasons';
                $topHeader = [
                    $this->medal->name,
                    $seasonName,
                    $mode->toUrlSlug(),
                ];

                $header = [
                    'Player',
                    'Place',
                    'Count',
                    'HoursPlayed',
                ];

                $topTen = $results->slice(0, 10);
                $topHundred = $results->slice(0, 100);
                $topThousand = $results->slice(0, 1000);

                foreach ([$topTen, $topHundred, $topThousand] as $resultSet) {
                    $writer = Writer::createFromString();
                    $writer->insertOne($topHeader);
                    $writer->insertOne($header);
                    $writer->insertAll($resultSet->map(function (ServiceRecord $record) {
                        return [
                            'player' => $record->player->gamertag,
                            'place' => $record->place,
                            'count' => $record->value,
                            'hours' => $record->time_played,
                        ];
                    }));

                    $slug = Str::slug($seasonName.'-'.$mode->toUrlSlug().'-'.$this->medal->name.'-top-'.count($resultSet));

                    $folder = 'medals/'.Str::slug($seasonName).'/'.$mode->toUrlSlug().'/';
                    Storage::disk('public')->put($folder.$slug.'.csv', $writer->toString());
                }
            }
        });
    }

    private function obtainResults(Mode $mode): Collection
    {
        $query = ServiceRecord::query()
            ->with('player')
            ->leftJoin('players', 'players.id', '=', 'service_records.player_id')
            ->where('is_cheater', false)
            ->where('mode', $mode->value)
            ->selectRaw('ROW_NUMBER() OVER(ORDER BY value DESC) AS place,
                CAST(JSON_EXTRACT(medals, "$.'.$this->medal->id.'") as unsigned) as value,
                mode, total_seconds_played, player_id')
            ->whereRaw('CAST(JSON_EXTRACT(medals, "$.'.$this->medal->id.'") as unsigned) > 0')
            ->orderByRaw('value DESC, total_seconds_played DESC');

        if ($this->season) {
            $query->where('season_key', $this->season->key);
        } else {
            $query->whereNull('season_key');
        }

        return $query->limit(1000)->get();
    }
}

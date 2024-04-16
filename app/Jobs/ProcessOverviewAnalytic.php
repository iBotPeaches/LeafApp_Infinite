<?php

namespace App\Jobs;

use App\Enums\QueueName;
use App\Models\Game;
use App\Models\Gamevariant;
use App\Models\Map;
use App\Models\Overview;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ProcessOverviewAnalytic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $mapName,
        public array $mapIds
    ) {
        $this->onQueue(QueueName::RECORDS);
    }

    public function handle(): void
    {
        $mapSlug = Str::slug($this->mapName);

        // HACK - Obtain image URL
        /** @var Map|null $map */
        $map = Map::query()
            ->whereIn('id', $this->mapIds)
            ->first();

        /** @var Overview $overview */
        $overview = Overview::query()
            ->firstOrNew([
                'slug' => $mapSlug,
            ], [
                'name' => $this->mapName,
            ]);

        $overview->name = $this->mapName;
        $overview->image = $map?->thumbnail_url ?? '';
        $overview->save();

        // Find all the maps (versions) with the first time played.
        $this->parseOverviewMaps($overview);

        // From those map versions, find all the gametypes played.
        $this->parseOverviewGametypes($overview);
    }

    private function parseOverviewMaps(Overview $overview): void
    {
        foreach ($this->mapIds as $mapId) {
            $releasedAt = Game::query()
                ->select('occurred_at')
                ->where('id', $mapId)
                ->orderByDesc('occurred_at')
                ->value('occurred_at');

            $overview->maps()->create([
                'map_id' => $mapId,
                'released_at' => $releasedAt,
            ]);
        }
    }

    private function parseOverviewGametypes(Overview $overview): void
    {
        $gametypeIds = Game::query()
            ->select('gamevariant_id')
            ->whereIn('map_id', $this->mapIds)
            ->whereNotNull('playlist_id')
            ->distinct()
            ->pluck('gamevariant_id');

        $gamevariants = Gamevariant::query()
            ->whereIn('id', $gametypeIds)
            ->get();

        // TODO DEDUPE GAMETYPES, since like 16 different iterations of Slayer
        $test = '';
    }
}

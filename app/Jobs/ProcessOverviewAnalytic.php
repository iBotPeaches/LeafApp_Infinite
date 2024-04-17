<?php

namespace App\Jobs;

use App\Enums\BaseGametype;
use App\Enums\QueueName;
use App\Models\Game;
use App\Models\Gamevariant;
use App\Models\Map;
use App\Models\Overview;
use App\Support\Gametype\GametypeHelper;
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

        $this->parseOverviewMaps($overview);
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

            $overview->maps()->updateOrCreate([
                'map_id' => $mapId,
            ], [
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

        $variantMapping = [];
        Gamevariant::query()
            ->whereIn('id', $gametypeIds)
            ->each(function (Gamevariant $gamevariant) use (&$variantMapping) {
                $baseMode = GametypeHelper::findBaseGametype($gamevariant->name);
                $variantMapping[$baseMode->value][] = $gamevariant->id;
            });

        foreach ($variantMapping as $baseMode => $variantIds) {
            $overview->gametypes()->updateOrCreate([
                'gametype' => $baseMode,
            ], [
                'name' => BaseGametype::fromValue($baseMode)->description,
                'gamevariant_ids' => $variantIds,
            ]);
        }
    }
}

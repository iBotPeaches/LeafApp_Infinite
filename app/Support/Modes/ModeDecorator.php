<?php

declare(strict_types=1);

namespace App\Support\Modes;

use App\Enums\Outcome;
use App\Models\Category;
use App\Models\Map;
use App\Models\Player;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ModeDecorator
{
    public Collection $modes;

    public function __construct(Player $player, int $season = null)
    {
        $modes = DB::query()
            ->from('game_players')
            ->select('outcome', 'map_id', 'category_id', new Expression('COUNT(*) as total'))
            ->where('player_id', $player->id)
            ->where('playlists.is_ranked', true)
            ->join('games', 'game_players.game_id', '=', 'games.id')
            ->join('playlists', 'games.playlist_id', '=', 'playlists.id')
            ->groupBy('map_id', 'category_id', 'outcome')
            ->get()
            ->map(function ($result) {
                return new ModeResult($result);
            })
            ->filter(function (ModeResult $modeResult) {
                return $modeResult->outcome->in([
                    Outcome::WIN(),
                    Outcome::LOSS()
                ]);
            });

        $mapIds = $modes->pluck('mapId');
        $categoryIds = $modes->pluck('categoryId');

        $maps = Map::query()
            ->whereIn('id', $mapIds)
            ->get()
            ->keyBy('id');

        $categories = Category::query()
            ->whereIn('id', $categoryIds)
            ->get()
            ->keyBy('id');

        $this->modes = $modes->map(function (ModeResult $modeResult) use ($maps, $categories) {
            $modeResult->map = $maps[$modeResult->mapId];
            $modeResult->category = $categories[$modeResult->categoryId];
            return $modeResult;
        });
    }

    public function bestModes()
    {

    }

    public function worseModes()
    {

    }
}

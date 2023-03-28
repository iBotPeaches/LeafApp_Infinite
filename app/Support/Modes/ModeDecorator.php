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
        $sums = [];
        $season = $season === -1 ? null : $season;

        $query = DB::query()
            ->from('game_players')
            ->select('outcome', 'map_id', 'category_id', new Expression('COUNT(*) as total'))
            ->where('player_id', $player->id)
            ->where('playlists.is_ranked', true)
            ->join('games', 'game_players.game_id', '=', 'games.id')
            ->join('playlists', 'games.playlist_id', '=', 'playlists.id')
            ->groupBy('map_id', 'category_id', 'outcome');

        if ($season) {
            $query->where('games.season_number', $season);
        }

        $modes = $query
            ->get()
            ->map(function ($result) {
                return new ModeResult($result);
            })
            ->filter(function (ModeResult $modeResult) {
                return $modeResult->outcome->in([
                    Outcome::WIN(),
                    Outcome::LOSS(),
                ]);
            })
            ->each(function (ModeResult $modeResult) use (&$sums) {
                if (! isset($sums[$modeResult->key()])) {
                    $sums[$modeResult->key()] = 0;
                }

                $sums[$modeResult->key()] += $modeResult->total;
            })
            ->each(function (ModeResult $modeResult) use ($sums) {
                $modeResult->percentWon = ($modeResult->total / $sums[$modeResult->key()]) * 100;
                $modeResult->summedTotal = $sums[$modeResult->key()];
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

        $this->modes = $modes
            ->map(function (ModeResult $modeResult) use ($maps, $categories) {
                $modeResult->map = $maps[$modeResult->mapId];
                $modeResult->category = $categories[$modeResult->categoryId];

                return $modeResult;
            });
    }

    public function bestModes(int $count = 10): Collection
    {
        return $this->modes
            ->filter(function (ModeResult $modeResult) {
                return $modeResult->total > 5 && $modeResult->outcome->is(Outcome::WIN());
            })
            ->sortByDesc('percentWon')
            ->take($count);
    }

    public function worseModes(int $count = 10): Collection
    {
        return $this->modes
            ->filter(function (ModeResult $modeResult) {
                return $modeResult->total > 5 && $modeResult->outcome->is(Outcome::LOSS());
            })
            ->sortByDesc('percentWon')
            ->take($count);
    }
}

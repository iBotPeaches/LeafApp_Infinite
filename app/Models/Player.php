<?php

namespace App\Models;

use App\Models\Contracts\HasHaloDotApi;
use App\Models\Pivots\PersonalResult;
use App\Services\HaloDotApi\InfiniteInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property string $gamertag
 * @property string $service_tag
 * @property string $emblem_url
 * @property string $backdrop_url
 * @property-read Game[]|Collection $games
 */
class Player extends Model implements HasHaloDotApi
{
    use HasFactory;

    public $guarded = [
        'id'
    ];

    public function getRouteKeyName(): string
    {
        return 'gamertag';
    }

    public static function fromGamertag(string $gamertag): self
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return self::query()
            ->where('gamertag', $gamertag)
            ->firstOrNew([
                'gamertag' => $gamertag
            ]);
    }

    public static function fromHaloDotApi(array $payload): ?self
    {
        $player = self::fromGamertag(Arr::get($payload, 'additional.gamertag'));

        $player->service_tag = Arr::get($payload, 'data.service_tag');
        $player->emblem_url = Arr::get($payload, 'data.emblem_url');
        $player->backdrop_url = Arr::get($payload, 'data.backdrop_image_url');

        if ($player->isDirty()) {
            $player->saveOrFail();
        }

        return $player;
    }

    public function updateFromHaloDotApi(bool $forceUpdate = false): void
    {
        /** @var InfiniteInterface $client */
        $client = resolve(InfiniteInterface::class);
        $client->matches($this, $forceUpdate);
    }

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_players')
            ->as('personal')
            ->using(PersonalResult::class)
            ->withPivot([
                'outcome',
                'kills',
                'deaths',
                'kd',
                'kda',
                'accuracy',
                'score',
                'rank'
            ]);
    }
}

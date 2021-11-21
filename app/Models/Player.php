<?php

namespace App\Models;

use App\Models\Contracts\HasHaloDotApi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property string $gamertag
 * @property string $service_tag
 * @property string $emblem_url
 * @property string $backdrop_url
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
        // @phpstan-ignore-next-line
        $player = self::fromGamertag(Arr::get($payload, 'additional.gamertag'));

        $player->fill([
            'service_tag' => Arr::get($payload, 'data.service_tag'),
            'emblem_url' => Arr::get($payload, 'data.emblem_url'),
            'backdrop_url' => Arr::get($payload, 'data.backdrop_image_url')
        ]);

        return $player;
    }
}

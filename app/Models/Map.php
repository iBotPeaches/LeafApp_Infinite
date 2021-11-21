<?php

namespace App\Models;

use App\Models\Contracts\HasHaloDotApi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property string $uuid
 * @property string $version
 * @property string $name
 * @property string $thumbnail_url
 */
class Map extends Model implements HasHaloDotApi
{
    use HasFactory;

    public $guarded = [
        'id'
    ];

    public $timestamps = false;

    public static function fromHaloDotApi(array $payload): ?self
    {
        $mapId = Arr::get($payload, 'asset.id');

        /** @var Map $map */
        $map = self::query()
            ->where('uuid', $mapId)
            ->firstOrNew([
                'uuid' => $mapId
            ]);

        $map->name = Arr::get($payload, 'name');
        $map->version = Arr::get($payload, 'asset.version');
        $map->thumbnail_url = Arr::get($payload, 'asset.thumbnail_url');

        if ($map->isDirty()) {
            $map->saveOrFail();
        }

        return $map;
    }
}

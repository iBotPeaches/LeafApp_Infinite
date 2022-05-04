<?php

namespace App\Models;

use App\Models\Contracts\HasHaloDotApi;
use Database\Factories\MapFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $thumbnail_url
 * @property-read string $image
 * @method static MapFactory factory(...$parameters)
 */
class Map extends Model implements HasHaloDotApi
{
    use HasFactory;

    public $guarded = [
        'id'
    ];

    public $timestamps = false;

    public function getImageAttribute(): string
    {
        $filename = Str::slug($this->name) . '.jpg';

        if (File::exists(public_path('images/maps/' . $filename))) {
            return asset('images/maps/' . $filename);
        }

        return $this->thumbnail_url;
    }

    public static function fromHaloDotApi(array $payload): ?self
    {
        $mapId = Arr::get($payload, 'level_id', Arr::get($payload, 'properties.level_id'));

        /** @var Map $map */
        $map = self::query()
            ->where('uuid', $mapId)
            ->firstOrNew([
                'uuid' => $mapId
            ]);

        $map->name = Arr::get($payload, 'name');
        $map->thumbnail_url = Arr::get($payload, 'thumbnail_url', Arr::get($payload, 'asset.thumbnail_url'));

        if ($map->isDirty()) {
            $map->saveOrFail();
        }

        return $map;
    }
}

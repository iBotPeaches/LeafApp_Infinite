<?php

namespace App\Models;

use App\Models\Contracts\HasHaloDotApi;
use Database\Factories\MapFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int|null $level_id
 * @property string $uuid
 * @property string $name
 * @property string $thumbnail_url
 * @property-read string $image
 * @property-read Level|null $level
 *
 * @method static MapFactory factory(...$parameters)
 */
class Map extends Model implements HasHaloDotApi
{
    use HasFactory;

    public $guarded = [
        'id',
        'level_id',
    ];

    public $timestamps = false;

    public function getImageAttribute(): string
    {
        $filename = Str::slug($this->name).'.jpg';

        if (File::exists(public_path('images/maps/'.$filename))) {
            return asset('images/maps/'.$filename);
        }

        return $this->thumbnail_url;
    }

    public static function fromHaloDotApi(array $payload): ?self
    {
        $mapId = Arr::get($payload, 'id');
        $mapName = Arr::get($payload, 'name');

        $level = Level::fromMetadata($payload);

        /** @var Map $map */
        $map = self::query()
            ->where('uuid', $mapId)
            ->firstOrNew([
                'uuid' => $mapId,
            ]);

        $map->name = $mapName;
        $map->thumbnail_url = Arr::get($payload, 'image_urls.thumbnail');
        $map->level()->associate($level);

        if ($map->isDirty()) {
            $map->saveOrFail();
        }

        return $map;
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }
}

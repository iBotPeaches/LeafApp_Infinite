<?php

namespace App\Models;

use App\Models\Contracts\HasDotApi;
use App\Models\Contracts\HasDotApiMetadata;
use Database\Factories\LevelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $thumbnail_url
 * @property-read string $image
 *
 * @method static LevelFactory factory(...$parameters)
 */
class Level extends Model implements HasDotApi, HasDotApiMetadata
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public $timestamps = false;

    public const string UNKNOWN_LEVEL_UUID = '00000000-0000-0000-0000-000000000000';

    public static function fromMetadata(array $payload): ?self
    {
        return self::query()
            ->where('uuid', (string) Arr::get($payload, 'properties.level_id'))
            ->first() ?? self::asUnknownLevel();
    }

    private static function asUnknownLevel(): ?self
    {
        return self::query()
            ->where('uuid', self::UNKNOWN_LEVEL_UUID)
            ->first();
    }

    public static function fromDotApi(array $payload): ?self
    {
        $levelId = Arr::get($payload, 'id');
        $levelName = Arr::get($payload, 'name');

        // HACK - The API started adding " - Ranked" to Ranked Maps.
        // This has caused data accuracy issues due to older ranked maps not having this suffix.
        // We will remove this suffix from the name to ensure we can match the level correctly.
        $levelName = Str::remove(' - Ranked', $levelName);

        /** @var Level $level */
        $level = self::query()
            ->where('uuid', $levelId)
            ->firstOrNew([
                'uuid' => $levelId,
            ]);

        $level->name = $levelName;
        $level->thumbnail_url = Arr::get($payload, 'image_urls.thumbnail');

        if ($level->isDirty()) {
            $level->save();
        }

        return $level;
    }
}

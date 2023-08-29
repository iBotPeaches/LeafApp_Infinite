<?php

namespace App\Models;

use App\Models\Contracts\HasDotApi;
use App\Models\Contracts\HasDotApiMetadata;
use Database\Factories\LevelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $thumbnail_url
 * @property-read string $image
 *
 * @method static LevelFactory factory(...$parameters)
 */
class Level extends Model implements HasDotApiMetadata, HasDotApi
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public $timestamps = false;

    public const UNKNOWN_LEVEL_UUID = '00000000-0000-0000-0000-000000000000';

    public static function fromMetadata(array $payload): ?self
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return self::query()
            ->where('uuid', (string) Arr::get($payload, 'properties.level_id'))
            ->first() ?? self::asUnknownLevel();
    }

    private static function asUnknownLevel(): ?self
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return self::query()
            ->where('uuid', self::UNKNOWN_LEVEL_UUID)
            ->first();
    }

    public static function fromDotApi(array $payload): ?self
    {
        $levelId = Arr::get($payload, 'id');
        $levelName = Arr::get($payload, 'name');

        /** @var Level $level */
        $level = self::query()
            ->where('uuid', $levelId)
            ->firstOrNew([
                'uuid' => $levelId,
            ]);

        $level->name = $levelName;
        $level->thumbnail_url = Arr::get($payload, 'image_urls.thumbnail');

        if ($level->isDirty()) {
            $level->saveOrFail();
        }

        return $level;
    }
}

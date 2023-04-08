<?php

namespace App\Models;

use App\Models\Contracts\HasHaloDotApi;
use App\Models\Contracts\HasHaloDotApiMetadata;
use Database\Factories\LevelFactory;
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
 *
 * @method static LevelFactory factory(...$parameters)
 */
class Level extends Model implements HasHaloDotApiMetadata, HasHaloDotApi
{
    use HasFactory;

    public $guarded = [
        'id',
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

    public static function fromMetadata(array $payload): ?static
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return self::query()
            ->where('uuid', Arr::get($payload, 'id'))
            ->firstOrFail();
    }

    public static function fromHaloDotApi(array $payload): ?self
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

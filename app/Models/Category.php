<?php

namespace App\Models;

use App\Models\Contracts\HasHaloDotApi;
use App\Models\Contracts\HasHaloDotApiMetadata;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $thumbnail_url
 *
 * @method static CategoryFactory factory(...$parameters)
 */
class Category extends Model implements HasHaloDotApi, HasHaloDotApiMetadata
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public $timestamps = false;

    public static function fromMetadata(array $payload): ?self
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return self::query()
            ->where('uuid', Arr::get($payload, 'properties.category_id'))
            ->firstOrFail();
    }

    public static function fromHaloDotApi(array $payload): ?self
    {
        $categoryId = (string) Arr::get($payload, 'id');

        /** @var Category $category */
        $category = self::query()
            ->where('uuid', $categoryId)
            ->firstOrNew([
                'uuid' => $categoryId,
            ]);

        $category->name = Arr::get($payload, 'name');
        $category->thumbnail_url = Arr::get($payload, 'image_urls.thumbnail');

        if ($category->isDirty()) {
            $category->saveOrFail();
        }

        return $category;
    }
}

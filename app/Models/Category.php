<?php

namespace App\Models;

use App\Models\Contracts\HasHaloDotApi;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $thumbnail_url
 *
 * @method static CategoryFactory factory(...$parameters)
 */
class Category extends Model implements HasHaloDotApi
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public $timestamps = false;

    public static function fromHaloDotApi(array $payload): ?self
    {
        $categoryId = (string) Arr::get($payload, 'category_id', Arr::get($payload, 'properties.category_id'));
        $name = Str::after(Arr::get($payload, 'name'), ':');

        // Due to gametypes having the same categoryId as base. We will key again to prevent wiping custom gametypes vs base modes.
        $key = md5($categoryId.Str::lower($name));

        /** @var Category $category */
        $category = self::query()
            ->where('uuid', $key)
            ->firstOrNew([
                'uuid' => $key,
            ]);

        $category->name = $name;
        $category->thumbnail_url = Arr::get(
            $payload,
            'thumbnail_url',
            Arr::get($payload, 'asset.thumbnail_url')
        );

        if ($category->isDirty()) {
            $category->saveOrFail();
        }

        return $category;
    }
}

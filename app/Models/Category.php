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
class Category extends Model implements HasHaloDotApi
{
    use HasFactory;

    public $guarded = [
        'id'
    ];

    public $timestamps = false;

    public static function fromHaloDotApi(array $payload): ?self
    {
        $categoryId = Arr::get($payload, 'asset.id');

        /** @var Category $category */
        $category = self::query()
            ->where('uuid', $categoryId)
            ->firstOrNew([
                'uuid' => $categoryId
            ]);

        $category->name = Arr::get($payload, 'name');
        $category->version = Arr::get($payload, 'asset.version');
        $category->thumbnail_url = Arr::get($payload, 'asset.thumbnail_url');

        if ($category->isDirty()) {
            $category->saveOrFail();
        }

        return $category;
    }
}

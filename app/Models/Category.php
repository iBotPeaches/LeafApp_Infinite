<?php

namespace App\Models;

use App\Models\Contracts\HasDotApi;
use App\Models\Contracts\HasDotApiMetadata;
use App\Services\DotApi\Exceptions\UnknownCategoryException;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Throwable;

use function Sentry\captureException;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $thumbnail_url
 *
 * @method static CategoryFactory factory(...$parameters)
 */
class Category extends Model implements HasDotApi, HasDotApiMetadata
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public $timestamps = false;

    public static function fromMetadata(array $payload): ?self
    {
        try {
            return self::query()
                ->where('uuid', (string) Arr::get($payload, 'properties.category_id'))
                ->firstOrFail();
        } catch (Throwable $e) {
            captureException(UnknownCategoryException::fromPayload($payload));

            return self::query()
                ->where('name', 'Unknown')
                ->first();
        }
    }

    public static function fromDotApi(array $payload): ?self
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
            $category->save();
        }

        return $category;
    }
}

<?php

namespace App\Models;

use App\Enums\MedalCategory;
use App\Enums\MedalType;
use App\Models\Contracts\HasHaloDotApi;
use Database\Factories\MedalFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property MedalCategory $category
 * @property MedalType $type
 * @property string $thumbnail_url
 * @property-read string $image
 * @property-read string $color
 * @method static MedalFactory factory(...$parameters)
 */
class Medal extends Model implements HasHaloDotApi
{
    use HasFactory;

    public $guarded = [
        'id'
    ];

    public $casts = [
        'category' => MedalCategory::class,
        'type' => MedalType::class,
    ];

    public $timestamps = false;

    public $incrementing = false;

    public function setCategoryAttribute(string $value): void
    {
        $category = is_numeric($value)
            ? MedalCategory::fromValue((int) $value)
            : MedalCategory::coerce(Str::upper($value));

        if (empty($category)) {
            throw new \InvalidArgumentException('Invalid Category Enum (' . $value . ')');
        }

        $this->attributes['category'] = $category->value;
    }

    public function setTypeAttribute(string $value): void
    {
        $type = is_numeric($value)
            ? MedalType::fromValue((int) $value)
            : MedalType::coerce(Str::upper($value));

        if (empty($type)) {
            throw new \InvalidArgumentException('Invalid Type Enum (' . $value . ')');
        }

        $this->attributes['type'] = $type->value;
    }

    public function getImageAttribute(): string
    {
        return asset('images/medals/' . $this->id . '.png');
    }

    public function getColorAttribute(): string
    {
        return match ((int) $this->type->value) {
            MedalType::LEGENDARY => 'orange',
            MedalType::MYTHIC => 'purple',
            MedalType::HEROIC => 'info',
            default => 'primary'
        };
    }

    public static function fromHaloDotApi(array $payload): ?self
    {
        $medalId = Arr::get($payload, 'id');

        /** @var Medal $medal */
        $medal = self::query()
            ->where('id', $medalId)
            ->firstOrNew();

        $medal->id = Arr::get($payload, 'id');
        $medal->name = Arr::get($payload, 'name');
        $medal->description = Arr::get($payload, 'description');
        $medal->category = Arr::get($payload, 'category', 'unknown');
        $medal->type = Arr::get($payload, 'difficulty');
        $medal->thumbnail_url = Arr::get($payload, 'image_urls.small');

        if ($medal->isDirty()) {
            $medal->saveOrFail();
        }

        return $medal;
    }
}

<?php

namespace App\Models;

use App\Enums\MedalDifficulty;
use App\Enums\MedalType;
use App\Models\Contracts\HasDotApi;
use Database\Factories\MedalFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property MedalType $type
 * @property MedalDifficulty $difficulty
 * @property-read string $image
 * @property-read string $color
 * @property-read string $tooltip_color
 * @property-read string $text_color
 *
 * @method static MedalFactory factory(...$parameters)
 */
class Medal extends Model implements HasDotApi, Sitemapable
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public $casts = [
        'type' => MedalType::class,
        'difficulty' => MedalDifficulty::class,
    ];

    public $timestamps = false;

    public $incrementing = false;

    public function setTypeAttribute(string $value): void
    {
        $category = is_numeric($value)
            ? MedalType::fromValue((int) $value)
            : MedalType::coerce(Str::upper($value));

        if (empty($category)) {
            throw new \InvalidArgumentException('Invalid Type Enum ('.$value.')');
        }

        $this->attributes['type'] = $category->value;
    }

    public function setDifficultyAttribute(string $value): void
    {
        $type = is_numeric($value)
            ? MedalDifficulty::fromValue((int) $value)
            : MedalDifficulty::coerce(Str::upper($value));

        if (empty($type)) {
            throw new \InvalidArgumentException('Invalid Difficulty Enum ('.$value.')');
        }

        $this->attributes['difficulty'] = $type->value;
    }

    public function getImageAttribute(): string
    {
        return asset('images/medals/'.$this->id.'.png');
    }

    public function getColorAttribute(): string
    {
        return match ((int) $this->difficulty->value) {
            MedalDifficulty::LEGENDARY => 'orange',
            MedalDifficulty::MYTHIC => 'purple',
            MedalDifficulty::HEROIC => 'info',
            default => 'primary'
        };
    }

    public function getTooltipColorAttribute(): string
    {
        return match ((int) $this->difficulty->value) {
            MedalDifficulty::LEGENDARY => 'has-tooltip-orange',
            MedalDifficulty::MYTHIC => 'has-tooltip-purple',
            MedalDifficulty::HEROIC => 'has-tooltip-info',
            default => 'has-tooltip-success'
        };
    }

    public function getTextColorAttribute(): string
    {
        return 'has-text-'.$this->color;
    }

    public function toSitemapTag(): Url|string|array
    {
        $url = new Url(route('medalLeaderboard', $this));
        $url->setChangeFrequency('always');

        return $url;
    }

    public static function fromDotApi(array $payload): ?self
    {
        $medalId = Arr::get($payload, 'id');

        /** @var Medal $medal */
        $medal = self::query()
            ->where('id', $medalId)
            ->firstOrNew();

        $medal->id = Arr::get($payload, 'id');
        $medal->name = Arr::get($payload, 'name');
        $medal->description = Arr::get($payload, 'description');
        $medal->type = Arr::get($payload, 'properties.type', 'unknown');
        $medal->difficulty = Arr::get($payload, 'attributes.difficulty');

        if ($medal->isDirty()) {
            $medal->save();
        }

        return $medal;
    }
}

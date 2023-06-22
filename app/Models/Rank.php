<?php

namespace App\Models;

use App\Models\Contracts\HasHaloDotApi;
use Database\Factories\MapFactory;
use Database\Factories\RankFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $subtitle
 * @property int|null $grade
 * @property int|null $tier
 * @property string $type
 * @property int $threshold
 * @property int $required
 *
 * @method static RankFactory factory(...$parameters)
 */
class Rank extends Model implements HasHaloDotApi
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public $timestamps = false;

    public function getIconAttribute(): string
    {
        $filename = Str::slug($this->id).'.jpg';

        return asset('images/ranks/icons/'.$filename);
    }

    public static function fromHaloDotApi(array $payload, Rank $previous = null): ?self
    {
        $rankId = Arr::get($payload, 'rank');

        /** @var Rank $rank */
        $rank = self::query()
            ->where('id', $rankId)
            ->firstOrNew();

        $rank->id = $rankId;
        $rank->name = Arr::get($payload, 'title');
        $rank->subtitle = Arr::get($payload, 'subtitle');
        $rank->grade = Arr::get($payload, 'attributes.grade');
        $rank->tier = Arr::get($payload, 'attributes.tier');
        $rank->type = Arr::get($payload, 'properties.type');
        $rank->threshold = (int)Arr::get($payload, 'properties.threshold');

        $lastThreshold = $previous?->threshold ?? 0;
        $rank->required = max(0, $rank->threshold - $lastThreshold);

        if ($rank->isDirty()) {
            $rank->saveOrFail();
        }

        return $rank;
    }
}

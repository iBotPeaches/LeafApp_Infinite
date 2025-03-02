<?php

namespace App\Models;

use App\Models\Contracts\HasDotApi;
use Database\Factories\RankFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property string $name
 * @property string $subtitle
 * @property int|null $grade
 * @property int|null $tier
 * @property string $type
 * @property int $threshold
 * @property int $required
 * @property-read string $icon
 * @property-read string $largeIcon
 * @property-read string $title
 *
 * @method static RankFactory factory(...$parameters)
 */
class Rank extends Model implements HasDotApi
{
    use HasFactory;

    public $incrementing = false;

    public $guarded = [
        'id',
    ];

    public $timestamps = false;

    public function getIconAttribute(): string
    {
        $filename = $this->id.'.png';

        return asset('images/ranks/icons/'.$filename);
    }

    public function getLargeIconAttribute(): string
    {
        $filename = $this->id.'.png';

        return asset('images/ranks/large/'.$filename);
    }

    public function getTitleAttribute(): string
    {
        $title = [];
        $title[] = $this->name;

        if ($this->subtitle) {
            $title[] = $this->subtitle;
        }

        if ($this->grade) {
            $title[] = '(Tier '.$this->grade.')';
        }

        return implode(' ', $title);
    }

    public static function fromDotApi(array $payload, ?Rank $previous = null): ?self
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
        $rank->threshold = (int) Arr::get($payload, 'properties.threshold');

        $lastThreshold = $previous->threshold ?? 0;
        $rank->required = max(0, $rank->threshold - $lastThreshold);

        if ($rank->isDirty()) {
            $rank->save();
        }

        return $rank;
    }
}

<?php

namespace App\Models;

use App\Enums\Experience;
use App\Models\Contracts\HasHaloDotApi;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $uuid
 * @property int $category_id
 * @property int $map_id
 * @property boolean $is_ffa
 * @property boolean $is_scored
 * @property Experience $experience
 * @property Carbon $occurred_at
 * @property int $duration_seconds
 * @property-read Category $category
 * @property-read Map $map
 */
class Game extends Model implements HasHaloDotApi
{
    use HasFactory;

    public $guarded = [
        'id',
        'category_id',
        'map_id'
    ];

    public $dates = [
        'occurred_at'
    ];

    public $casts = [
        'experience' => Experience::class
    ];

    public $with = [
        'category',
        'map'
    ];

    public $timestamps = false;

    public function setExperienceAttribute(string $value): void
    {
        $experience = Experience::coerce($value);
        if (empty($experience)) {
            throw new \InvalidArgumentException('Invalid Experience Enum (' . $value . ')');
        }

        $this->attributes['experience'] = $experience->value;
    }

    public static function fromHaloDotApi(array $payload): ?self
    {
        $gameId = Arr::get($payload, 'id');
        $category = Category::fromHaloDotApi(Arr::get($payload, 'details.category'));
        $map = Map::fromHaloDotApi(Arr::get($payload, 'details.map'));

        /** @var Game $game */
        $game = self::query()
            ->where('uuid', $gameId)
            ->firstOrNew([
                'uuid' => $gameId
            ]);

        $game->category()->associate($category);
        $game->map()->associate($map);
        $game->is_ffa = !(bool) Arr::get($payload, 'teams.enabled');
        $game->is_scored = (bool) Arr::get($payload, 'teams.scoring');
        $game->experience = Arr::get($payload, 'experience');
        $game->occurred_at = Arr::get($payload, 'played_at');
        $game->duration_seconds = Arr::get($payload, 'duration.seconds');

        if ($game->isDirty()) {
            $game->saveOrFail();
        }

        return $game;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }
}

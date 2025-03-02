<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Mode;
use Database\Factories\MedalAnalyticFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $player_id
 * @property int $season_id
 * @property int $medal_id
 * @property Mode $mode
 * @property int $value
 * @property int $place
 * @property int $total_seconds_played
 * @property-read float $time_played
 * @property-read Player $player
 * @property-read Season|null $season
 * @property-read Medal $medal
 *
 * @method static MedalAnalyticFactory factory(...$parameters)
 */
class MedalAnalytic extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $casts = [
        'mode' => Mode::class,
    ];

    public $guarded = [
        'id',
    ];

    public static function purgeSeason(Medal $medal, ?Season $season = null): void
    {
        $query = self::query()
            ->where('medal_id', $medal->id);

        if ($season) {
            $query->where('season_id', $season->id);
        } else {
            $query->whereNull('season_id');
        }

        $query->delete();
    }

    public function getTimePlayedAttribute(): float
    {
        return now()->addSeconds($this->total_seconds_played)->diffInHours(absolute: true);
    }

    /**
     * @return BelongsTo<Medal, $this>
     */
    public function medal(): BelongsTo
    {
        return $this->belongsTo(Medal::class);
    }

    /**
     * @return BelongsTo<Player, $this>
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * @return BelongsTo<Season, $this>
     */
    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }
}

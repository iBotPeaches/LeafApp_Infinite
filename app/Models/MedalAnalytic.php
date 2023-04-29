<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $player_id
 * @property int $season_id
 * @property int $medal_id
 * @property int $value
 * @property int $place
 * @property float $hours_played
 * @property-read Player $player
 * @property-read Season|null $season
 * @property-read Medal $medal
 */
class MedalAnalytic extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public static function purgeSeason(Medal $medal, Season $season = null): void
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

    public function medal(): BelongsTo
    {
        return $this->belongsTo(Medal::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }
}

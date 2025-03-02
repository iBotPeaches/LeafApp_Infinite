<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\OverviewStatFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $overview_id
 * @property int|null $overview_gametype_id
 * @property int|null $overview_map_id
 * @property int $total_matches
 * @property int $total_seconds_played
 * @property int $total_players
 * @property int $total_unique_players
 * @property int $total_dnf
 * @property int $total_kills
 * @property int $total_deaths
 * @property int $total_assists
 * @property int $total_suicides
 * @property int $total_medals
 * @property float $average_kd
 * @property float $average_kda
 * @property float $average_accuracy
 * @property-read float $time_played
 * @property-read float $quit_rate
 * @property-read float $average_game_length
 * @property-read Overview $overview
 * @property-read OverviewGametype|null $gametype
 * @property-read OverviewMap|null $map
 *
 * @method static OverviewStatFactory factory(...$parameters)
 */
class OverviewStat extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public $timestamps = false;

    public function getTimePlayedAttribute(): float
    {
        return now()->addSeconds($this->total_seconds_played)->diffInHours(absolute: true);
    }

    public function getQuitRateAttribute(): float
    {
        if ($this->total_players === 0) {
            return 0;
        }

        return ($this->total_dnf / $this->total_players) * 100;
    }

    public function getAverageGameLengthAttribute(): float
    {
        if ($this->total_matches === 0) {
            return 0;
        }

        $averageGameLength = $this->total_seconds_played / $this->total_matches;

        return now()->addSeconds($averageGameLength)->diffInMinutes(absolute: true);
    }

    /**
     * @return BelongsTo<Overview, $this>
     */
    public function overview(): BelongsTo
    {
        return $this->belongsTo(Overview::class);
    }

    /**
     * @return BelongsTo<OverviewGametype, $this>
     */
    public function gametype(): BelongsTo
    {
        return $this->belongsTo(OverviewGametype::class, 'overview_gametype_id');
    }

    /**
     * @return BelongsTo<OverviewMap, $this>
     */
    public function map(): BelongsTo
    {
        return $this->belongsTo(OverviewMap::class, 'overview_map_id');
    }
}

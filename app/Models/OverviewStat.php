<?php

declare(strict_types=1);

namespace App\Models;

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
 * @property-read Overview $overview
 * @property-read OverviewGametype|null $gametype
 * @property-read OverviewMap|null $map
 */
class OverviewStat extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public $timestamps = false;

    public function overview(): BelongsTo
    {
        return $this->belongsTo(Overview::class);
    }

    public function gametype(): BelongsTo
    {
        return $this->belongsTo(OverviewGametype::class);
    }

    public function map(): BelongsTo
    {
        return $this->belongsTo(OverviewMap::class);
    }
}

<?php

namespace App\Models;

use App\Enums\Outcome;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $player_id
 * @property int $match_id
 * @property int $rank
 * @property Outcome $outcome
 * @property float $kd
 * @property float $kda
 * @property int $score
 * @property int $kills
 * @property int $deaths
 * @property int $assists
 * @property int $betrayals
 * @property int $suicides
 * @property int $vehicle_destroys
 * @property int $vehicle_hijacks
 * @property int $medal_count
 * @property int $damage_taken
 * @property int $damage_dealt
 * @property int $shots_fired
 * @property int $shots_landed
 * @property int $shots_missed
 * @property float $accuracy
 * @property int $rounds_won
 * @property int $rounds_lost
 * @property int $rounds_tied
 * @property int $kills_melee
 * @property int $kills_grenade
 * @property int $kills_headshot
 * @property int $kills_power
 * @property int $assists_emp
 * @property int $assists_driver
 * @property int $assists_callout
 * @property-read Player $player
 * @property-read Match $match
 */
class MatchPlayer extends Model
{
    use HasFactory;

    public $casts = [
        'outcome' => Outcome::class
    ];

    public $timestamps = false;

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(Match::class);
    }
}

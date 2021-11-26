<?php

namespace App\Models;

use App\Models\Contracts\HasHaloDotApi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property int $player_id
 * @property float $kd
 * @property float $kda
 * @property int $total_score
 * @property int $total_matches
 * @property int $matches_won
 * @property int $matches_lost
 * @property int $matches_tied
 * @property int $matches_left
 * @property int $total_seconds_played
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
 * @property int $kills_melee
 * @property int $kills_grenade
 * @property int $kills_headshot
 * @property int $kills_power
 * @property int $assists_emp
 * @property int $assists_driver
 * @property int $assists_callout
 * @property-read Player $player
 */
class ServiceRecord extends Model implements HasHaloDotApi
{
    use HasFactory;

    public $guarded = [
        'id'
    ];

    public static function fromHaloDotApi(array $payload): ?self
    {
        /** @var Player $player */
        $player = Arr::get($payload, 'player');

        /** @var ServiceRecord $serviceRecord */
        $serviceRecord = ServiceRecord::query()
            ->where('player_id', $player->id)
            ->firstOrNew();

        $serviceRecord->player()->associate($player);
        $serviceRecord->kd = Arr::get($payload, 'data.kdr');
        $serviceRecord->kda = Arr::get($payload, 'data.kda');
        $serviceRecord->total_score = Arr::get($payload, 'data.total_score');
        $serviceRecord->total_matches = Arr::get($payload, 'data.matches_played');
        $serviceRecord->matches_won = Arr::get($payload, 'data.breakdowns.matches.wins');
        $serviceRecord->matches_lost = Arr::get($payload, 'data.breakdowns.matches.losses');
        $serviceRecord->matches_tied = Arr::get($payload, 'data.breakdowns.matches.draws');
        $serviceRecord->matches_left = Arr::get($payload, 'data.breakdowns.matches.left');
        $serviceRecord->total_seconds_played = Arr::get($payload, 'data.time_played.seconds');
        $serviceRecord->kills = Arr::get($payload, 'data.summary.kills');
        $serviceRecord->deaths = Arr::get($payload, 'data.summary.deaths');
        $serviceRecord->assists = Arr::get($payload, 'data.summary.assists');
        $serviceRecord->betrayals = Arr::get($payload, 'data.summary.betrayals');
        $serviceRecord->suicides = Arr::get($payload, 'data.summary.suicides');
        $serviceRecord->vehicle_destroys = Arr::get($payload, 'data.summary.vehicles.destroys');
        $serviceRecord->vehicle_hijacks = Arr::get($payload, 'data.summary.vehicles.hijacks');
        $serviceRecord->medal_count = Arr::get($payload, 'data.summary.medals');
        $serviceRecord->damage_taken = Arr::get($payload, 'data.damage.taken');
        $serviceRecord->damage_dealt = Arr::get($payload, 'data.damage.dealt');
        $serviceRecord->shots_fired = Arr::get($payload, 'data.shots.fired');
        $serviceRecord->shots_landed = Arr::get($payload, 'data.shots.landed');
        $serviceRecord->shots_missed = Arr::get($payload, 'data.shots.missed');
        $serviceRecord->accuracy = Arr::get($payload, 'data.shots.accuracy');
        $serviceRecord->kills_melee = Arr::get($payload, 'data.breakdowns.kills.melee');
        $serviceRecord->kills_grenade = Arr::get($payload, 'data.breakdowns.kills.grenades');
        $serviceRecord->kills_headshot = Arr::get($payload, 'data.breakdowns.kills.headshots');
        $serviceRecord->kills_power = Arr::get($payload, 'data.breakdowns.kills.power_weapons');
        $serviceRecord->assists_emp = Arr::get($payload, 'data.breakdowns.assists.emp');
        $serviceRecord->assists_driver = Arr::get($payload, 'data.breakdowns.assists.driver');
        $serviceRecord->assists_callout = Arr::get($payload, 'data.breakdowns.assists.callouts');

        if ($serviceRecord->isDirty()) {
            $serviceRecord->saveOrFail();
            return $serviceRecord;
        }

        return null;
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}

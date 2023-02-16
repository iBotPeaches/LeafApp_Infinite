<?php

namespace App\Models;

use App\Enums\Mode;
use App\Models\Contracts\HasHaloDotApi;
use App\Models\Traits\HasAccuracy;
use App\Models\Traits\HasMedals;
use Database\Factories\ServiceRecordFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $player_id
 * @property Mode $mode
 * @property int|null $season_number
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
 * @property array $medals
 * @property-read Player $player
 * @property-read float $win_percent
 * @property-read float $average_score
 * @property-read string $time_played
 * @property-read string $kd_color
 * @property-read string $kda_color
 * @property-read string $win_percent_color
 * @property-read Collection<int, Medal> $hydrated_medals
 *
 * @method static ServiceRecordFactory factory(...$parameters)
 */
class ServiceRecord extends Model implements HasHaloDotApi
{
    use HasFactory, HasMedals, HasAccuracy;

    public $guarded = [
        'id',
    ];

    public $casts = [
        'total_matches' => 'int',
        'medals' => 'array',
        'mode' => Mode::class,
    ];

    public $touches = [
        'player',
    ];

    public function getWinPercentAttribute(): float
    {
        if ($this->total_matches == 0) {
            return 100;
        }

        return ($this->matches_won / $this->total_matches) * 100;
    }

    public function getAverageScoreAttribute(): float
    {
        if ($this->total_matches == 0) {
            return $this->total_score;
        }

        return $this->total_score / $this->total_matches;
    }

    public function getTimePlayedAttribute(): int
    {
        return now()->addSeconds($this->total_seconds_played)->diffInHours();
    }

    public function getWinPercentColorAttribute(): string
    {
        switch (true) {
            case $this->win_percent > 50:
                return 'has-text-success';

            case $this->win_percent > 35 && $this->win_percent <= 50:
                return 'has-text-warning';

            default:
            case $this->win_percent < 35:
                return 'has-text-danger';
        }
    }

    public function getKdaColorAttribute(): string
    {
        switch (true) {
            case $this->kda >= 2:
                return 'has-text-success';

            case $this->kda > 1 && $this->kda < 2:
                return 'has-text-warning';

            default:
            case $this->kda < 1:
                return 'has-text-danger';
        }
    }

    public function getKdColorAttribute(): string
    {
        switch (true) {
            case $this->kd >= 1:
                return 'has-text-success';

            case $this->kd > 0.5 && $this->kd < 1:
                return 'has-text-warning';

            default:
            case $this->kd < 0.5:
                return 'has-text-danger';
        }
    }

    public static function fromHaloDotApi(array $payload): ?self
    {
        /** @var Player $player */
        $player = Arr::get($payload, '_leaf.player');
        /** @var Mode $mode */
        $mode = Arr::get($payload, '_leaf.filter');
        $season = Arr::get($payload, '_leaf.season');

        /** @var ServiceRecord $serviceRecord */
        $serviceRecord = ServiceRecord::query()
            ->where('player_id', $player->id)
            ->where('mode', $mode)
            ->where('season_number', $season)
            ->firstOrNew();

        $serviceRecord->player()->associate($player);
        $serviceRecord->mode = $mode;
        $serviceRecord->season_number = $season;
        $serviceRecord->kd = (float) Arr::get($payload, 'core.kdr');
        $serviceRecord->kda = (float) Arr::get($payload, 'core.kda');
        $serviceRecord->total_score = (int) Arr::get($payload, 'core.scores.personal');
        $serviceRecord->total_matches = Arr::get($payload, 'matches.total');
        $serviceRecord->matches_won = Arr::get($payload, 'matches.outcomes.wins');
        $serviceRecord->matches_lost = Arr::get($payload, 'matches.outcomes.losses');
        $serviceRecord->matches_tied = Arr::get($payload, 'matches.outcomes.draws');
        $serviceRecord->matches_left = Arr::get($payload, 'matches.outcomes.left');
        $serviceRecord->total_seconds_played = Arr::get($payload, 'time_played.seconds');
        $serviceRecord->kills = Arr::get($payload, 'core.summary.kills');
        $serviceRecord->deaths = Arr::get($payload, 'core.summary.deaths');
        $serviceRecord->assists = Arr::get($payload, 'core.summary.assists');
        $serviceRecord->betrayals = Arr::get($payload, 'core.summary.betrayals');
        $serviceRecord->suicides = Arr::get($payload, 'core.summary.suicides');
        $serviceRecord->vehicle_destroys = Arr::get($payload, 'core.summary.vehicles.destroys');
        $serviceRecord->vehicle_hijacks = Arr::get($payload, 'core.summary.vehicles.hijacks');
        $serviceRecord->medal_count = Arr::get($payload, 'core.summary.medals');
        $serviceRecord->damage_taken = Arr::get($payload, 'core.damage.taken');
        $serviceRecord->damage_dealt = Arr::get($payload, 'core.damage.dealt');
        $serviceRecord->shots_fired = Arr::get($payload, 'core.shots.fired');
        $serviceRecord->shots_landed = Arr::get($payload, 'core.shots.landed');
        $serviceRecord->shots_missed = Arr::get($payload, 'core.shots.missed');
        $serviceRecord->accuracy = (float) Arr::get($payload, 'core.shots.accuracy');
        $serviceRecord->kills_melee = Arr::get($payload, 'core.breakdowns.kills.melee');
        $serviceRecord->kills_grenade = Arr::get($payload, 'core.breakdowns.kills.grenades');
        $serviceRecord->kills_headshot = Arr::get($payload, 'core.breakdowns.kills.headshots');
        $serviceRecord->kills_power = Arr::get($payload, 'core.breakdowns.kills.power_weapons');
        $serviceRecord->assists_emp = Arr::get($payload, 'core.breakdowns.assists.emp');
        $serviceRecord->assists_driver = Arr::get($payload, 'core.breakdowns.assists.driver');
        $serviceRecord->assists_callout = Arr::get($payload, 'core.breakdowns.assists.callouts');

        $serviceRecord->medals = collect((array) Arr::get($payload, 'core.breakdowns.medals'))
            ->mapWithKeys(function (array $medal) {
                return [
                    $medal['id'] => $medal['count'],
                ];
            })->toArray();

        if (Arr::get($payload, '_leaf.privacy.public') === false) {
            $serviceRecord->player->is_private = true;
        } elseif ($serviceRecord->player->is_private) {
            $serviceRecord->player->is_private = false;
        }

        if ($serviceRecord->player->isDirty(['is_private'])) {
            $serviceRecord->player->saveOrFail();
        }

        if ($serviceRecord->isDirty() && ! $serviceRecord->player->is_private) {
            $serviceRecord->saveOrFail();
        }

        return $serviceRecord;
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function scopeOfSeason(Builder $query, int $season): Builder
    {
        if ($season === -1) {
            return $query->whereNull('season_number');
        }

        return $query->where('season_number', $season);
    }
}

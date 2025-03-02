<?php

namespace App\Models;

use App\Enums\Mode;
use App\Models\Contracts\HasDotApi;
use App\Models\Traits\HasAccuracy;
use App\Models\Traits\HasMedals;
use App\Support\Session\SeasonSession;
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
 * @property string|null $season_key
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
class ServiceRecord extends Model implements HasDotApi
{
    use HasAccuracy, HasFactory, HasMedals;

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

    public function getTimePlayedAttribute(): float
    {
        return now()->addSeconds($this->total_seconds_played)->diffInHours(absolute: true);
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

    public static function fromDotApi(array $payload): ?self
    {
        /** @var Player $player */
        $player = Arr::get($payload, '_leaf.player');
        /** @var Mode $mode */
        $mode = Arr::get($payload, '_leaf.filter');
        /** @var Season|null $season */
        $season = Arr::get($payload, '_leaf.season');

        /** @var ServiceRecord $serviceRecord */
        $serviceRecord = ServiceRecord::query()
            ->where('player_id', $player->id)
            ->where('mode', $mode)
            ->where('season_key', $season?->key)
            ->firstOrNew();

        $serviceRecord->player()->associate($player);
        $serviceRecord->mode = $mode;
        $serviceRecord->season_key = $season?->key;
        $serviceRecord->kd = (float) Arr::get($payload, 'stats.core.kdr');
        $serviceRecord->kda = (float) Arr::get($payload, 'stats.core.kda');
        $serviceRecord->total_score = max((int) Arr::get($payload, 'stats.core.scores.personal'), 0);
        $serviceRecord->total_matches = Arr::get($payload, 'matches.completed');
        $serviceRecord->matches_won = Arr::get($payload, 'matches.wins');
        $serviceRecord->matches_lost = Arr::get($payload, 'matches.losses');
        $serviceRecord->matches_tied = Arr::get($payload, 'matches.ties');
        $serviceRecord->matches_left = Arr::get($payload, 'matches.left', 0);
        $serviceRecord->total_seconds_played = Arr::get($payload, 'time_played.seconds');
        $serviceRecord->kills = Arr::get($payload, 'stats.core.summary.kills');
        $serviceRecord->deaths = Arr::get($payload, 'stats.core.summary.deaths');
        $serviceRecord->assists = Arr::get($payload, 'stats.core.summary.assists');
        $serviceRecord->betrayals = Arr::get($payload, 'stats.core.summary.betrayals');
        $serviceRecord->suicides = Arr::get($payload, 'stats.core.summary.suicides');
        $serviceRecord->vehicle_destroys = Arr::get($payload, 'stats.core.summary.vehicles.destroys');
        $serviceRecord->vehicle_hijacks = Arr::get($payload, 'stats.core.summary.vehicles.hijacks');
        $serviceRecord->medal_count = Arr::get($payload, 'stats.core.summary.medals.total');
        $serviceRecord->damage_taken = Arr::get($payload, 'stats.core.damage.taken');
        $serviceRecord->damage_dealt = Arr::get($payload, 'stats.core.damage.dealt');
        $serviceRecord->shots_fired = Arr::get($payload, 'stats.core.shots.fired');
        $serviceRecord->shots_landed = Arr::get($payload, 'stats.core.shots.hit');
        $serviceRecord->shots_missed = Arr::get($payload, 'stats.core.shots.missed');
        $serviceRecord->accuracy = (float) Arr::get($payload, 'stats.core.shots.accuracy');
        $serviceRecord->kills_melee = Arr::get($payload, 'stats.core.breakdown.kills.melee');
        $serviceRecord->kills_grenade = Arr::get($payload, 'stats.core.breakdown.kills.grenades');
        $serviceRecord->kills_headshot = Arr::get($payload, 'stats.core.breakdown.kills.headshots');
        $serviceRecord->kills_power = Arr::get($payload, 'stats.core.breakdown.kills.power_weapons');
        $serviceRecord->assists_emp = Arr::get($payload, 'stats.core.breakdown.assists.emp');
        $serviceRecord->assists_driver = Arr::get($payload, 'stats.core.breakdown.assists.driver');
        $serviceRecord->assists_callout = Arr::get($payload, 'stats.core.breakdown.assists.callouts');

        $serviceRecord->medals = collect((array) Arr::get($payload, 'stats.core.breakdown.medals'))
            ->mapWithKeys(function (array $medal) {
                return [
                    $medal['id'] => $medal['count'],
                ];
            })->toArray();

        if ($serviceRecord->total_seconds_played === 0 && $serviceRecord->total_score === 0 && $serviceRecord->mode->is(Mode::MATCHMADE_PVP())) {
            $serviceRecord->player->is_private = true;
        } elseif ($serviceRecord->player->is_private && $serviceRecord->mode->is(Mode::MATCHMADE_PVP())) {
            $serviceRecord->player->is_private = false;
        }

        // Old seasons shouldn't be used to determine private-ness, since new players will be marked as inactive.
        // Just flag the account if the all seasons (merged) returns no data.
        if ($serviceRecord->player->isDirty(['is_private']) && ($season === null || $season->key === SeasonSession::$allSeasonKey)) {
            $serviceRecord->player->save();
        }

        if ($serviceRecord->isDirty() && ! $serviceRecord->player->is_private) {
            $serviceRecord->save();
        }

        return $serviceRecord;
    }

    /**
     * @return BelongsTo<Player, $this>
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function scopeOfSeason(Builder $query, Season $season): Builder
    {
        if ($season->key === SeasonSession::$allSeasonKey) {
            return $query->whereNull('season_key');
        }

        return $query->where('season_key', $season->key);
    }
}

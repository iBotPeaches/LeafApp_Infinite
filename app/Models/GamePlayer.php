<?php

namespace App\Models;

use App\Enums\Outcome;
use App\Models\Contracts\HasHaloDotApi;
use Database\Factories\GamePlayerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
 * @property-read Game $game
 * @method static GamePlayerFactory factory(...$parameters)
 */
class GamePlayer extends Model implements HasHaloDotApi
{
    use HasFactory;

    public $casts = [
        'outcome' => Outcome::class
    ];

    public $timestamps = false;

    public $with = [
        'game'
    ];

    public function setOutcomeAttribute(string $value): void
    {
        $outcome = is_numeric($value) ? Outcome::fromValue((int) $value) : Outcome::coerce(Str::upper($value));
        if (empty($outcome)) {
            throw new \InvalidArgumentException('Invalid Outcome Enum (' . $value . ')');
        }

        $this->attributes['outcome'] = $outcome->value;
    }

    public static function fromHaloDotApi(array $payload): ?self
    {
        /** @var Player $player */
        $player = Arr::get($payload, '_leaf.player');

        /** @var Game $game */
        $game = Arr::get($payload, '_leaf.game');

        /** @var GamePlayer $gamePlayer */
        $gamePlayer = self::query()
            ->where('player_id', $player->id)
            ->where('game_id', $game->id)
            ->firstOrNew();

        $gamePlayer->player()->associate($player);
        $gamePlayer->game()->associate($game);
        $gamePlayer->rank = Arr::get($payload, 'player.rank');
        $gamePlayer->outcome = Arr::get($payload, 'player.outcome');
        $gamePlayer->kd = Arr::get($payload, 'player.stats.core.kdr');
        $gamePlayer->kda = Arr::get($payload, 'player.stats.core.kda');
        $gamePlayer->score = Arr::get($payload, 'player.stats.core.score');
        $gamePlayer->kills = Arr::get($payload, 'player.stats.core.summary.kills');
        $gamePlayer->deaths = Arr::get($payload, 'player.stats.core.summary.deaths');
        $gamePlayer->assists = Arr::get($payload, 'player.stats.core.summary.assists');
        $gamePlayer->betrayals = Arr::get($payload, 'player.stats.core.summary.betrayals');
        $gamePlayer->suicides = Arr::get($payload, 'player.stats.core.summary.suicides');
        $gamePlayer->vehicle_destroys = Arr::get($payload, 'player.stats.core.summary.vehicles.destroys');
        $gamePlayer->vehicle_hijacks = Arr::get($payload, 'player.stats.core.summary.vehicles.hijacks');
        $gamePlayer->medal_count = Arr::get($payload, 'player.stats.core.summary.medals');
        $gamePlayer->damage_taken = Arr::get($payload, 'player.stats.core.damage.taken');
        $gamePlayer->damage_dealt = Arr::get($payload, 'player.stats.core.damage.dealt');
        $gamePlayer->shots_fired = Arr::get($payload, 'player.stats.core.shots.fired');
        $gamePlayer->shots_landed = Arr::get($payload, 'player.stats.core.shots.landed');
        $gamePlayer->shots_missed = Arr::get($payload, 'player.stats.core.shots.missed');
        $gamePlayer->accuracy = Arr::get($payload, 'player.stats.core.shots.accuracy');
        $gamePlayer->rounds_won = Arr::get($payload, 'player.stats.core.rounds.won');
        $gamePlayer->rounds_lost = Arr::get($payload, 'player.stats.core.rounds.lost');
        $gamePlayer->rounds_tied = Arr::get($payload, 'player.stats.core.rounds.tied');
        $gamePlayer->kills_melee = Arr::get($payload, 'player.stats.core.breakdowns.kills.melee');
        $gamePlayer->kills_grenade = Arr::get($payload, 'player.stats.core.breakdowns.kills.grenades');
        $gamePlayer->kills_headshot = Arr::get($payload, 'player.stats.core.breakdowns.kills.headshots');
        $gamePlayer->kills_power = Arr::get($payload, 'player.stats.core.breakdowns.kills.power_weapons');
        $gamePlayer->assists_emp = Arr::get($payload, 'player.stats.core.breakdowns.assists.emp');
        $gamePlayer->assists_driver = Arr::get($payload, 'player.stats.core.breakdowns.assists.driver');
        $gamePlayer->assists_callout = Arr::get($payload, 'player.stats.core.breakdowns.assists.callouts');

        if ($gamePlayer->isDirty()) {
            $gamePlayer->saveOrFail();
        }

        return $gamePlayer;
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}

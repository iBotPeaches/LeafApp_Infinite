<?php

namespace App\Models;

use App\Enums\Outcome;
use App\Models\Contracts\HasHaloDotApi;
use App\Models\Traits\HasKd;
use App\Models\Traits\HasOutcome;
use App\Models\Traits\HasScoring;
use Database\Factories\GamePlayerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

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
 * @property-read GameTeam $team
 * @method static GamePlayerFactory factory(...$parameters)
 */
class GamePlayer extends Model implements HasHaloDotApi
{
    use HasFactory, HasOutcome, HasKd, HasScoring;

    public $casts = [
        'outcome' => Outcome::class
    ];

    public $timestamps = false;

    public $with = [
        'game'
    ];

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

        // We use this function between two calls. The match history endpoint & the match retrieve endpoint.
        // The individual match endpoint returns all players, so it keyed by player, thus doesn't need "player"
        $prefix = Arr::has($payload, 'player') ? 'player.' : null;
        if (empty($prefix)) {
            $team = $game->findTeamFromId(Arr::get($payload, 'team.id'));
            $gamePlayer->team()->associate($team);
        }

        $gamePlayer->player()->associate($player);
        $gamePlayer->game()->associate($game);
        $gamePlayer->rank = Arr::get($payload, $prefix . 'rank');
        $gamePlayer->outcome = Arr::get($payload, $prefix . 'outcome');
        $gamePlayer->kd = Arr::get($payload, $prefix . 'stats.core.kdr');
        $gamePlayer->kda = Arr::get($payload, $prefix . 'stats.core.kda');
        $gamePlayer->score = Arr::get($payload, $prefix . 'stats.core.score');
        $gamePlayer->kills = Arr::get($payload, $prefix . 'stats.core.summary.kills');
        $gamePlayer->deaths = Arr::get($payload, $prefix . 'stats.core.summary.deaths');
        $gamePlayer->assists = Arr::get($payload, $prefix . 'stats.core.summary.assists');
        $gamePlayer->betrayals = Arr::get($payload, $prefix . 'stats.core.summary.betrayals');
        $gamePlayer->suicides = Arr::get($payload, $prefix . 'stats.core.summary.suicides');
        $gamePlayer->vehicle_destroys = Arr::get($payload, $prefix . 'stats.core.summary.vehicles.destroys');
        $gamePlayer->vehicle_hijacks = Arr::get($payload, $prefix . 'stats.core.summary.vehicles.hijacks');
        $gamePlayer->medal_count = Arr::get($payload, $prefix . 'stats.core.summary.medals');
        $gamePlayer->damage_taken = Arr::get($payload, $prefix . 'stats.core.damage.taken');
        $gamePlayer->damage_dealt = Arr::get($payload, $prefix . 'stats.core.damage.dealt');
        $gamePlayer->shots_fired = Arr::get($payload, $prefix . 'stats.core.shots.fired');
        $gamePlayer->shots_landed = Arr::get($payload, $prefix . 'stats.core.shots.landed');
        $gamePlayer->shots_missed = Arr::get($payload, $prefix . 'stats.core.shots.missed');
        $gamePlayer->accuracy = Arr::get($payload, $prefix . 'stats.core.shots.accuracy');
        $gamePlayer->rounds_won = Arr::get($payload, $prefix . 'stats.core.rounds.won');
        $gamePlayer->rounds_lost = Arr::get($payload, $prefix . 'stats.core.rounds.lost');
        $gamePlayer->rounds_tied = Arr::get($payload, $prefix . 'stats.core.rounds.tied');
        $gamePlayer->kills_melee = Arr::get($payload, $prefix . 'stats.core.breakdowns.kills.melee');
        $gamePlayer->kills_grenade = Arr::get($payload, $prefix . 'stats.core.breakdowns.kills.grenades');
        $gamePlayer->kills_headshot = Arr::get($payload, $prefix . 'stats.core.breakdowns.kills.headshots');
        $gamePlayer->kills_power = Arr::get($payload, $prefix . 'stats.core.breakdowns.kills.power_weapons');
        $gamePlayer->assists_emp = Arr::get($payload, $prefix . 'stats.core.breakdowns.assists.emp');
        $gamePlayer->assists_driver = Arr::get($payload, $prefix . 'stats.core.breakdowns.assists.driver');
        $gamePlayer->assists_callout = Arr::get($payload, $prefix . 'stats.core.breakdowns.assists.callouts');

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

    public function team(): BelongsTo
    {
        return $this->belongsTo(GameTeam::class, 'game_team_id');
    }
}

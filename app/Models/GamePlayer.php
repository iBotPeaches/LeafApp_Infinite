<?php

namespace App\Models;

use App\Enums\Outcome;
use App\Models\Contracts\HasHaloDotApi;
use App\Models\Traits\HasAccuracy;
use App\Models\Traits\HasCsr;
use App\Models\Traits\HasKd;
use App\Models\Traits\HasMedals;
use App\Models\Traits\HasOutcome;
use App\Models\Traits\HasScoring;
use Database\Factories\GamePlayerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $player_id
 * @property int $game_id
 * @property int $pre_csr
 * @property int $post_csr
 * @property int $rank
 * @property Outcome $outcome
 * @property boolean $was_at_start
 * @property boolean $was_at_end
 * @property boolean $was_inprogress_join
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
 * @property array $medals
 * @property-read Player $player
 * @property-read Game $game
 * @property-read GameTeam|null $team
 * @property-read Collection<int, Medal> $hydrated_medals
 * @method static GamePlayerFactory factory(...$parameters)
 */
class GamePlayer extends Model implements HasHaloDotApi
{
    use HasFactory, HasOutcome, HasKd, HasScoring, HasCsr, HasMedals, HasAccuracy;

    public $casts = [
        'medals' => 'array',
        'outcome' => Outcome::class
    ];

    public $timestamps = false;

    public $with = [
        'player',
        'team',
    ];

    public $touches = [
        'player'
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
            $team = $game->findTeamFromInternalId(Arr::get($payload, 'team.id'));
            $gamePlayer->team()->associate($team);
        }

        $gamePlayer->player()->associate($player);
        $gamePlayer->game()->associate($game);
        $gamePlayer->pre_csr = Arr::get($payload, $prefix . 'progression.csr.pre_match.value');
        $gamePlayer->post_csr = Arr::get($payload, $prefix . 'progression.csr.post_match.value');
        $gamePlayer->rank = Arr::get($payload, $prefix . 'rank');
        $gamePlayer->outcome = Arr::get($payload, $prefix . 'outcome');
        $gamePlayer->was_at_start ??= Arr::get($payload, $prefix . 'participation.presence.beginning', true);
        $gamePlayer->was_at_end ??= Arr::get($payload, $prefix . 'participation.presence.completion', true);
        $gamePlayer->was_inprogress_join ??= Arr::get($payload, $prefix . 'participation.joined_in_progress', false);
        $gamePlayer->kd = Arr::get($payload, $prefix . 'stats.core.kdr');
        $gamePlayer->kda = Arr::get($payload, $prefix . 'stats.core.kda');
        $gamePlayer->score ??= Arr::get($payload, $prefix . 'stats.core.scores.personal', 0);
        $gamePlayer->kills = Arr::get($payload, $prefix . 'stats.core.summary.kills');
        $gamePlayer->deaths = Arr::get($payload, $prefix . 'stats.core.summary.deaths');
        $gamePlayer->assists = Arr::get($payload, $prefix . 'stats.core.summary.assists');
        $gamePlayer->betrayals = Arr::get($payload, $prefix . 'stats.core.summary.betrayals');
        $gamePlayer->suicides = Arr::get($payload, $prefix . 'stats.core.summary.suicides');
        $gamePlayer->vehicle_destroys ??= Arr::get($payload, $prefix . 'stats.core.summary.vehicles.destroys', 0);
        $gamePlayer->vehicle_hijacks ??= Arr::get($payload, $prefix . 'stats.core.summary.vehicles.hijacks', 0);
        $gamePlayer->medal_count = Arr::get($payload, $prefix . 'stats.core.summary.medals');
        $gamePlayer->damage_taken = Arr::get($payload, $prefix . 'stats.core.damage.taken');
        $gamePlayer->damage_dealt = Arr::get($payload, $prefix . 'stats.core.damage.dealt');
        $gamePlayer->shots_fired = Arr::get($payload, $prefix . 'stats.core.shots.fired');
        $gamePlayer->shots_landed = Arr::get($payload, $prefix . 'stats.core.shots.landed');
        $gamePlayer->shots_missed = Arr::get($payload, $prefix . 'stats.core.shots.missed');
        $gamePlayer->accuracy = Arr::get($payload, $prefix . 'stats.core.shots.accuracy');
        $gamePlayer->rounds_won ??= Arr::get($payload, $prefix . 'stats.core.rounds.won', 0);
        $gamePlayer->rounds_lost ??= Arr::get($payload, $prefix . 'stats.core.rounds.lost', 0);
        $gamePlayer->rounds_tied ??= Arr::get($payload, $prefix . 'stats.core.rounds.tied', 0);
        $gamePlayer->kills_melee ??= Arr::get($payload, $prefix . 'stats.core.breakdowns.kills.melee', 0);
        $gamePlayer->kills_grenade ??= Arr::get($payload, $prefix . 'stats.core.breakdowns.kills.grenades', 0);
        $gamePlayer->kills_headshot ??= Arr::get($payload, $prefix . 'stats.core.breakdowns.kills.headshots', 0);
        $gamePlayer->kills_power ??= Arr::get($payload, $prefix . 'stats.core.breakdowns.kills.power_weapons', 0);
        $gamePlayer->assists_emp ??= Arr::get($payload, $prefix . 'stats.core.breakdowns.assists.emp', 0);
        $gamePlayer->assists_driver ??= Arr::get($payload, $prefix . 'stats.core.breakdowns.assists.driver', 0);
        $gamePlayer->assists_callout ??= Arr::get($payload, $prefix . 'stats.core.breakdowns.assists.callouts', 0);

        $gamePlayer->medals ??= collect((array)Arr::get($payload, $prefix . 'stats.core.breakdowns.medals', []))
            ->mapWithKeys(function (array $medal) {
                return [
                    $medal['id'] => $medal['count']
                ];
            })->toArray();

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

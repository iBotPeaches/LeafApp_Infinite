<?php

namespace App\Models;

use App\Enums\Outcome;
use App\Models\Contracts\HasDotApi;
use App\Models\Traits\HasAccuracy;
use App\Models\Traits\HasCsr;
use App\Models\Traits\HasKd;
use App\Models\Traits\HasMedals;
use App\Models\Traits\HasOutcome;
use App\Models\Traits\HasPerformance;
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
 * @property ?int $matches_remaining
 * @property int $rank
 * @property Outcome $outcome
 * @property bool|null $was_at_start
 * @property bool|null $was_at_end
 * @property bool|null $was_inprogress_join
 * @property float $kd
 * @property float $kda
 * @property int|null $score
 * @property float|null $mmr
 * @property int $kills
 * @property int $deaths
 * @property int $assists
 * @property int $betrayals
 * @property int $suicides
 * @property int|null $max_spree
 * @property int $vehicle_destroys
 * @property int $vehicle_hijacks
 * @property int $medal_count
 * @property int $damage_taken
 * @property int $damage_dealt
 * @property int $shots_fired
 * @property int $shots_landed
 * @property int $shots_missed
 * @property float $accuracy
 * @property int|null $rounds_won
 * @property int|null $rounds_lost
 * @property int|null $rounds_tied
 * @property int|null $kills_melee
 * @property int|null $kills_grenade
 * @property int|null $kills_headshot
 * @property int|null $kills_power
 * @property int|null $assists_emp
 * @property int|null $assists_driver
 * @property int|null $assists_callout
 * @property int|null $expected_kills
 * @property int|null $expected_deaths
 * @property array|null $medals
 * @property-read Player $player
 * @property-read Game $game
 * @property-read GameTeam|null $team
 * @property-read Collection<int, Medal> $hydrated_medals
 *
 * @method static GamePlayerFactory factory(...$parameters)
 */
class GamePlayer extends Model implements HasDotApi
{
    use HasAccuracy, HasCsr, HasFactory, HasKd, HasMedals, HasOutcome, HasPerformance, HasScoring;

    public $casts = [
        'medals' => 'array',
        'outcome' => Outcome::class,
    ];

    public $timestamps = false;

    public $with = [
        'player',
        'team',
    ];

    public $touches = [
        'player',
    ];

    public static function fromDotApi(array $payload): ?self
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
            $team = $game->findTeamFromInternalId((string) Arr::get($payload, 'properties.team.id'));
            $gamePlayer->team()->associate($team);
        }

        $gamePlayer->player()->associate($player);
        $gamePlayer->game()->associate($game);
        $gamePlayer->pre_csr ??= Arr::get($payload, $prefix.'progression.csr.pre_match.value');
        $gamePlayer->post_csr ??= Arr::get($payload, $prefix.'progression.csr.post_match.value');
        $gamePlayer->matches_remaining ??=
            Arr::get($payload, $prefix.'progression.csr.pre_match.measurement_matches_remaining');
        $gamePlayer->rank = Arr::get($payload, $prefix.'rank');
        $gamePlayer->outcome = Arr::get($payload, $prefix.'outcome');
        $gamePlayer->was_at_start ??= Arr::get($payload, $prefix.'participation.presence.beginning');
        $gamePlayer->was_at_end ??= Arr::get($payload, $prefix.'participation.presence.completion');
        $gamePlayer->was_inprogress_join ??= Arr::get($payload, $prefix.'participation.joined_in_progress');
        $gamePlayer->kd = Arr::get($payload, $prefix.'stats.core.kdr');
        $gamePlayer->kda = Arr::get($payload, $prefix.'stats.core.kda');
        $gamePlayer->score ??= Arr::get($payload, $prefix.'stats.core.scores.personal');
        $gamePlayer->mmr ??= Arr::get($payload, $prefix.'stats.mmr');
        $gamePlayer->kills = Arr::get($payload, $prefix.'stats.core.summary.kills');
        $gamePlayer->deaths = Arr::get($payload, $prefix.'stats.core.summary.deaths');
        $gamePlayer->assists = max((int) Arr::get($payload, $prefix.'stats.core.summary.assists'), 0);
        $gamePlayer->betrayals = Arr::get($payload, $prefix.'stats.core.summary.betrayals');
        $gamePlayer->suicides = Arr::get($payload, $prefix.'stats.core.summary.suicides');
        $gamePlayer->max_spree = Arr::get($payload, $prefix.'stats.core.summary.max_killing_spree');
        $gamePlayer->vehicle_destroys ??= Arr::get($payload, $prefix.'stats.core.summary.vehicles.destroys');
        $gamePlayer->vehicle_hijacks ??= Arr::get($payload, $prefix.'stats.core.summary.vehicles.hijacks');
        $gamePlayer->medal_count = Arr::get($payload, $prefix.'stats.core.summary.medals.total');
        $gamePlayer->damage_taken = Arr::get($payload, $prefix.'stats.core.damage.taken');
        $gamePlayer->damage_dealt = Arr::get($payload, $prefix.'stats.core.damage.dealt');
        $gamePlayer->shots_fired = Arr::get($payload, $prefix.'stats.core.shots.fired');
        $gamePlayer->shots_landed = Arr::get($payload, $prefix.'stats.core.shots.hit');
        $gamePlayer->shots_missed = Arr::get($payload, $prefix.'stats.core.shots.missed');
        $gamePlayer->accuracy = min((float) Arr::get($payload, $prefix.'stats.core.shots.accuracy'), 100);
        $gamePlayer->rounds_won ??= Arr::get($payload, $prefix.'stats.core.rounds.won');
        $gamePlayer->rounds_lost ??= Arr::get($payload, $prefix.'stats.core.rounds.lost');
        $gamePlayer->rounds_tied ??= Arr::get($payload, $prefix.'stats.core.rounds.tied');
        $gamePlayer->kills_melee ??= Arr::get($payload, $prefix.'stats.core.breakdown.kills.melee');
        $gamePlayer->kills_grenade ??= Arr::get($payload, $prefix.'stats.core.breakdown.kills.grenades');
        $gamePlayer->kills_headshot ??= Arr::get($payload, $prefix.'stats.core.breakdown.kills.headshots');
        $gamePlayer->kills_power ??= Arr::get($payload, $prefix.'stats.core.breakdown.kills.power_weapons');
        $gamePlayer->assists_emp ??= Arr::get($payload, $prefix.'stats.core.breakdown.assists.emp');
        $gamePlayer->assists_driver ??= Arr::get($payload, $prefix.'stats.core.breakdown.assists.driver');
        $gamePlayer->assists_callout ??= Arr::get($payload, $prefix.'stats.core.breakdown.assists.callouts');
        $gamePlayer->expected_kills ??= max(Arr::get($payload, $prefix.'performances.kills.expected'), 0);
        $gamePlayer->expected_deaths ??= max(Arr::get($payload, $prefix.'performances.deaths.expected'), 0);

        if (Arr::has($payload, 'stats.core.breakdown.medals')) {
            $gamePlayer->medals = collect((array) Arr::get($payload, $prefix.'stats.core.breakdown.medals'))
                ->mapWithKeys(function (array $medal) {
                    return [
                        $medal['id'] => $medal['count'],
                    ];
                })->toArray();
        }

        if ($gamePlayer->isDirty()) {
            $gamePlayer->save();
        }

        return $gamePlayer;
    }

    /**
     * @return BelongsTo<Player, $this>
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * @return BelongsTo<Game, $this>
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * @return BelongsTo<GameTeam, $this>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(GameTeam::class, 'game_team_id');
    }
}

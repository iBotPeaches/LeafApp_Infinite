<?php
declare(strict_types = 1);

namespace App\Models;

use App\Enums\Outcome;
use App\Models\Contracts\HasFaceItApi;
use App\Models\Pivots\MatchupPlayer;
use App\Models\Traits\HasOutcome;
use Database\Factories\MatchupTeamFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $matchup_id
 * @property string $faceit_id
 * @property string $name
 * @property int $points
 * @property Outcome $outcome
 * @property-read Matchup $matchup
 * @property-read Player[]|Collection $players
 * @property-read MatchupPlayer[]|Collection $faceitPlayers
 * @method static MatchupTeamFactory factory(...$parameters)
 */
class MatchupTeam extends Model implements HasFaceItApi
{
    use HasFactory, HasOutcome;

    public static string $byeTeamId = 'bye';

    public $guarded = [
        'id',
        'matchup_id',
    ];

    public $casts = [
        'outcome' => Outcome::class
    ];

    public $timestamps = false;

    public function isBye(): bool
    {
        return $this->faceit_id === self::$byeTeamId;
    }

    public function isWinner(): bool
    {
        return $this->outcome->is(Outcome::WIN());
    }

    public static function fromFaceItApi(array $payload): ?self
    {
        $teamInternalId = Arr::get($payload, '_leaf.team_id');
        $teamId = Arr::get($payload, 'faction_id');
        $matchupPayload = Arr::get($payload, '_leaf.raw_matchup');

        /** @var Matchup $matchup */
        $matchup = Arr::get($payload, '_leaf.matchup');

        /** @var MatchupTeam $team */
        $team = self::query()
            ->where('matchup_id', $matchup->id)
            ->where('faceit_id', $teamId)
            ->firstOrNew([
                'faceit_id' => $teamId
            ]);

        $team->matchup()->associate($matchup);
        $team->name = (string) $matchup->championship->is_ffa
            ? Arr::get($payload, 'roster.0.game_player_name', Arr::get($payload, 'name'))
            : Arr::get($payload, 'name');
        $team->points = (int)Arr::get($matchupPayload, 'results.score.' . $teamInternalId, 0);
        $team->outcome = Arr::get($matchupPayload, 'results.winner') === $teamInternalId
            ? Outcome::WIN()
            : Outcome::LOSS();

        if ($team->isDirty()) {
            $team->saveOrFail();
        }

        return $team;
    }

    public function getPlayer(): ?Player
    {
        return $this->players->first();
    }

    public function matchup(): BelongsTo
    {
        return $this->belongsTo(Matchup::class);
    }

    public function faceitPlayers(): HasMany
    {
        return $this->hasMany(MatchupPlayer::class);
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'matchup_player')
            ->as('faceit')
            ->using(MatchupPlayer::class)
            ->withPivot([
                'faceit_id',
                'faceit_name'
            ]);
    }
}

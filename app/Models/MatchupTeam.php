<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Outcome;
use App\Jobs\PullLogoFromMatchupTeam;
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
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $matchup_id
 * @property string $faceit_id
 * @property string $name
 * @property int|null $points
 * @property string $avatar
 * @property Outcome|null $outcome
 * @property-read Matchup $matchup
 * @property-read Collection<int, Player> $players
 * @property-read Collection<int, MatchupPlayer> $faceitPlayers
 *
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
        'outcome' => Outcome::class,
    ];

    public $timestamps = false;

    public function getAvatarAttribute(): string
    {
        $filename = $this->faceit_id.'.png';
        $avatar = asset('storage/images/logos/'.$filename);

        if (Storage::exists('public/images/logos/'.$filename)) {
            return $avatar;
        }

        return asset('images/logos/missing.png');
    }

    public function isBye(): bool
    {
        return $this->faceit_id === self::$byeTeamId;
    }

    public function isWinner(): bool
    {
        return $this->outcome && $this->outcome->is(Outcome::WIN());
    }

    public static function fromFaceItApi(array $payload): ?self
    {
        $teamInternalId = Arr::get($payload, '_leaf.team_id');
        $teamId = Arr::get($payload, 'faction_id');
        $matchupPayload = Arr::get($payload, '_leaf.raw_matchup');

        $winner = Arr::get($matchupPayload, 'results.winner');
        $points = Arr::get($matchupPayload, 'results.score.'.$teamInternalId);

        /** @var Matchup $matchup */
        $matchup = Arr::get($payload, '_leaf.matchup');

        /** @var MatchupTeam $team */
        $team = self::query()
            ->where('matchup_id', $matchup->id)
            ->where('faceit_id', $teamId)
            ->firstOrNew([
                'faceit_id' => $teamId,
            ]);

        $team->matchup()->associate($matchup);
        $team->name = (string) $matchup->championship->type->isFfa()
            ? Arr::get($payload, 'roster.0.game_player_name', Arr::get($payload, 'name'))
            : Arr::get($payload, 'name');

        $team->points = $points ? (int) $points : null;

        $team->outcome = $winner ? ($winner === $teamInternalId ? Outcome::WIN() : Outcome::LOSS()) : Outcome::DRAW();

        if ($team->isDirty()) {
            $team->save();
        }

        PullLogoFromMatchupTeam::dispatchSync($team, Arr::get($payload, 'avatar'));

        return $team;
    }

    public function getPlayer(): ?Player
    {
        return $this->players->first();
    }

    /**
     * @return BelongsTo<Matchup, $this>
     */
    public function matchup(): BelongsTo
    {
        return $this->belongsTo(Matchup::class);
    }

    /**
     * @return HasMany<MatchupPlayer, $this>
     */
    public function faceitPlayers(): HasMany
    {
        return $this->hasMany(MatchupPlayer::class);
    }

    /**
     * @return BelongsToMany<Player, $this, MatchupPlayer, 'faceit'>
     */
    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'matchup_player')
            ->as('faceit')
            ->using(MatchupPlayer::class)
            ->withPivot([
                'faceit_id',
                'faceit_name',
            ]);
    }
}

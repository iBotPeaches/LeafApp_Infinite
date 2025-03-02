<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Bracket;
use App\Enums\FaceItStatus;
use App\Enums\Outcome;
use App\Models\Contracts\HasFaceItApi;
use BenSampo\Enum\Enum;
use Carbon\Carbon;
use Database\Factories\MatchupFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;

/**
 * @property int $id
 * @property string $faceit_id
 * @property int $championship_id
 * @property int $round
 * @property int $group
 * @property int $best_of
 * @property FaceItStatus $status
 * @property Carbon|null $started_at
 * @property Carbon|null $ended_at
 * @property-read Championship $championship
 * @property-read Collection<int, MatchupTeam> $matchupTeams
 * @property-read Collection<int, Game> $games
 * @property-read MatchupTeam|null $winner
 * @property-read MatchupTeam|null $loser
 * @property-read MatchupTeam|null $team1
 * @property-read MatchupTeam|null $team2
 * @property-read string $score
 * @property-read Bracket $bracket
 * @property-read string $title
 * @property-read string $description
 * @property-read string $faceitUrl
 * @property-read string|null $length
 *
 * @method static MatchupFactory factory(...$parameters)
 */
class Matchup extends Model implements HasFaceItApi, Sitemapable
{
    use HasFactory;

    public $guarded = [
        'id',
        'championship_id',
    ];

    public $with = [
        'matchupTeams',
    ];

    public $casts = [
        'status' => FaceItStatus::class,
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public $timestamps = false;

    public function getRouteKeyName(): string
    {
        return 'faceit_id';
    }

    public function setStartedAtAttribute(string|Carbon|null $value): void
    {
        $this->attributes['started_at'] = $value instanceof Carbon
            ? $value
            : ($value ? Carbon::createFromTimestamp($value) : null);
    }

    public function setEndedAtAttribute(string|Carbon|null $value): void
    {
        $this->attributes['ended_at'] = $value instanceof Carbon
            ? $value
            : ($value ? Carbon::createFromTimestamp($value) : null);
    }

    public function setStatusAttribute(string $value): void
    {
        $type = is_numeric($value)
            ? FaceItStatus::fromValue((int) $value)
            : FaceItStatus::coerce($value);

        if (empty($type)) {
            throw new \InvalidArgumentException('Invalid Status Enum ('.$value.')');
        }

        $this->attributes['status'] = $type->value;
    }

    public function getWinnerAttribute(): ?MatchupTeam
    {
        return $this->matchupTeams->firstWhere('outcome', Outcome::WIN());
    }

    public function getLoserAttribute(): ?MatchupTeam
    {
        return $this->matchupTeams->firstWhere('outcome', Outcome::LOSS());
    }

    public function getTeam1Attribute(): ?MatchupTeam
    {
        return $this->matchupTeams->first();
    }

    public function getTeam2Attribute(): ?MatchupTeam
    {
        return $this->matchupTeams->last();
    }

    public function getScoreAttribute(): string
    {
        return (int) $this->winner?->points.' - '.(int) $this->loser?->points;
    }

    public function getTitleAttribute(): string
    {
        return $this->bracket?->description.' Round '.$this->round.
            ' '.$this->winner?->name.' vs '.$this->loser?->name;
    }

    public function getDescriptionAttribute(): string
    {
        return $this->winner?->name.' won '.$this->score;
    }

    public function getFaceitUrlAttribute(): string
    {
        return 'https://www.faceit.com/en/halo_infinite/room/'.$this->faceit_id;
    }

    public function getBracketAttribute(): ?Enum
    {
        return Bracket::coerce($this->group);
    }

    public function getLengthAttribute(): ?string
    {
        if (empty($this->started_at) || empty($this->ended_at)) {
            return null;
        }

        return $this->ended_at->diffInMinutes(date: $this->started_at, absolute: true).' minutes';
    }

    public function isCancelled(): bool
    {
        return $this->status->is(FaceItStatus::CANCELLED());
    }

    public function getTeamAt(int $place): ?MatchupTeam
    {
        return $this->matchupTeams->firstWhere('points', $place);
    }

    public static function fromFaceItApi(array $payload): ?self
    {
        $matchId = Arr::get($payload, 'match_id');

        /** @var Championship $championship */
        $championship = Arr::get($payload, '_leaf.championship');

        /** @var Matchup $matchup */
        $matchup = self::query()
            ->where('faceit_id', $matchId)
            ->firstOrNew([
                'faceit_id' => $matchId,
            ]);

        $matchup->championship()->associate($championship);
        $matchup->round = Arr::get($payload, 'round');
        $matchup->group = Arr::get($payload, 'group');
        $matchup->best_of = Arr::get($payload, 'best_of');
        $matchup->status = Arr::get($payload, 'status');
        $matchup->started_at = Arr::get(
            $payload,
            'started_at',
            Arr::get($payload, 'finished_at', $championship->started_at)
        );
        $matchup->ended_at = Arr::get($payload, 'finished_at');

        if ($matchup->isDirty()) {
            $matchup->save();
        }

        return $matchup;
    }

    public function toSitemapTag(): Url|string|array
    {
        $url = new Url(route('matchup', [$this->championship, $this]));
        $url->setLastModificationDate($this->ended_at ?? now());
        $url->setChangeFrequency('never');

        return $url;
    }

    /**
     * @return BelongsTo<Championship, $this>
     */
    public function championship(): BelongsTo
    {
        return $this->belongsTo(Championship::class);
    }

    /**
     * @return HasMany<MatchupTeam, $this>
     */
    public function matchupTeams(): HasMany
    {
        return $this->hasMany(MatchupTeam::class)
            ->orderByDesc('id');
    }

    /**
     * @return BelongsToMany<Game, $this>
     */
    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'matchup_game');
    }
}

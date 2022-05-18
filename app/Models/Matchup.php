<?php
declare(strict_types = 1);

namespace App\Models;

use App\Enums\Bracket;
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
 * @property Carbon $started_at
 * @property Carbon $ended_at
 * @property-read Championship $championship
 * @property-read MatchupTeam[]|Collection $matchupTeams
 * @property-read Game[]|Collection $games
 * @property-read MatchupTeam|null $winner
 * @property-read MatchupTeam|null $loser
 * @property-read string $score
 * @property-read Bracket $bracket
 * @property-read string $title
 * @property-read string $description
 * @property-read string $faceitUrl
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
        'matchupTeams'
    ];

    public $dates = [
        'started_at',
        'ended_at'
    ];

    public $timestamps = false;

    public function getRouteKeyName(): string
    {
        return 'faceit_id';
    }

    public function setStartedAtAttribute(string|Carbon $value): void
    {
        $this->attributes['started_at'] = $value instanceof Carbon ? $value : Carbon::createFromTimestampMs($value);
    }

    public function setEndedAtAttribute(string|Carbon $value): void
    {
        $this->attributes['ended_at'] = $value instanceof Carbon ? $value : Carbon::createFromTimestampMs($value);
    }

    public function getWinnerAttribute(): ?MatchupTeam
    {
        return $this->matchupTeams->firstWhere('outcome', Outcome::WIN());
    }

    public function getLoserAttribute(): ?MatchupTeam
    {
        return $this->matchupTeams->firstWhere('outcome', Outcome::LOSS());
    }

    public function getScoreAttribute(): string
    {
        return $this->winner?->points . ' - ' . $this->loser?->points;
    }

    public function getTitleAttribute(): string
    {
        return $this->bracket?->description . ' Round ' . $this->round .
            ' ' . $this->winner?->name . ' vs ' . $this->loser?->name;
    }

    public function getDescriptionAttribute(): string
    {
        return $this->winner?->name . ' won ' . $this->score;
    }

    public function getFaceitUrlAttribute(): string
    {
        return 'https://www.faceit.com/en/halo_infinite/room/' . $this->faceit_id;
    }

    public function getBracketAttribute(): ?Enum
    {
        return Bracket::coerce($this->group);
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
                'faceit_id' => $matchId
            ]);

        $matchup->championship()->associate($championship);
        $matchup->round = Arr::get($payload, 'round');
        $matchup->group = Arr::get($payload, 'group');
        $matchup->best_of = Arr::get($payload, 'best_of');
        $matchup->started_at = Arr::get(
            $payload,
            'started_at',
            Arr::get($payload, 'finished_at', $championship->started_at)
        );
        $matchup->ended_at = Arr::get($payload, 'finished_at');

        if ($matchup->isDirty()) {
            $matchup->saveOrFail();
        }

        return $matchup;
    }

    public function toSitemapTag(): Url|string|array
    {
        $url = new Url(route('matchup', [$this->championship, $this]));
        $url->setLastModificationDate($this->ended_at);
        $url->setChangeFrequency('never');

        return $url;
    }

    public function championship(): BelongsTo
    {
        return $this->belongsTo(Championship::class);
    }

    public function matchupTeams(): HasMany
    {
        return $this->hasMany(MatchupTeam::class);
    }

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'matchup_game');
    }
}

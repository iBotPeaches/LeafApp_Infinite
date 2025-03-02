<?php

namespace App\Models;

use App\Enums\Experience;
use App\Enums\Outcome;
use App\Jobs\PullAppearance;
use App\Models\Contracts\HasDotApi;
use App\Models\Pivots\PersonalResult;
use App\Services\DotApi\Enums\Mode;
use App\Services\DotApi\Enums\PlayerType;
use App\Services\DotApi\InfiniteInterface;
use Carbon\Carbon;
use Database\Factories\GameFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $uuid
 * @property int|null $category_id
 * @property int $map_id
 * @property int|null $playlist_id
 * @property int|null $gamevariant_id
 * @property bool $is_ffa
 * @property bool|null $is_lan
 * @property Experience $experience
 * @property Carbon $occurred_at
 * @property int $duration_seconds
 * @property int|null $season_number
 * @property int|null $season_version
 * @property string $version
 * @property bool $was_pulled
 * @property-read ?Category $category
 * @property-read Map $map
 * @property-read ?Playlist $playlist
 * @property-read ?Gamevariant $gamevariant
 * @property-read PersonalResult $personal
 * @property-read Collection<int, GamePlayer> $players
 * @property-read Collection<int, GameTeam> $teams
 * @property-read Collection<int, Analytic> $analytics
 * @property-read bool $outdated
 * @property-read string $name
 * @property-read string $description
 * @property-read GameTeam|null $winner
 * @property-read GameTeam|null $loser
 * @property-read string $score
 * @property-read string $duration
 *
 * @method static GameFactory factory(...$parameters)
 */
class Game extends Model implements HasDotApi
{
    use HasFactory;

    public $guarded = [
        'id',
        'category_id',
        'map_id',
        'playlist_id',
        'gamevariant_id',
    ];

    public $casts = [
        'experience' => Experience::class,
        'occurred_at' => 'datetime',
    ];

    public $with = [
        'category',
        'map',
        'playlist',
        'gamevariant',
    ];

    public $timestamps = false;

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $uuid = urldecode($value);

        try {
            return $this->query()
                ->where('uuid', $uuid)
                ->firstOrFail();
        } catch (ModelNotFoundException) {
            /** @var InfiniteInterface $client */
            $client = resolve(InfiniteInterface::class);
            $client->match($uuid);

            return $this->query()
                ->where('uuid', $uuid)
                ->first();
        }
    }

    public function setExperienceAttribute(string $value): void
    {
        $experience = is_numeric($value) ? Experience::fromValue((int) $value) : Experience::coerce($value);
        if (empty($experience)) {
            throw new \InvalidArgumentException('Invalid Experience Enum ('.$value.')');
        }

        $this->attributes['experience'] = $experience->value;
    }

    public function getNameAttribute(): string
    {
        return ($this->gamevariant->name ?? $this->category?->name).' on '.$this->map->name;
    }

    public function getDescriptionAttribute(): string
    {
        return ($this->gamevariant->name ?? $this->category?->name).' on '.
            $this->map->name.' in '.
            $this->experience->description.' at '.
            $this->occurred_at->toFormattedDateString().' with: '.
            $this->players->implode('player.gamertag', ', ');
    }

    public function getOutdatedAttribute(): bool
    {
        if (! $this->was_pulled) {
            return true;
        }

        return $this->version !== config('services.dotapi.version');
    }

    public function getDurationAttribute(): string
    {
        $minutes = intdiv($this->duration_seconds, 60);
        $seconds = $this->duration_seconds % 60;

        return $minutes.'min'.', '.$seconds.' '.Str::plural('sec', $seconds);
    }

    public function updateFromDotApi(): void
    {
        /** @var InfiniteInterface $client */
        $client = resolve(InfiniteInterface::class);

        $client->match($this->uuid);
    }

    public function findTeamFromInternalId(int|string $id): ?GameTeam
    {
        return $this->teams->firstWhere('internal_team_id', $id);
    }

    public function findTeamFromTeamId(int|string $id): ?GameTeam
    {
        return $this->teams->firstWhere('id', $id);
    }

    public function getWinnerAttribute(): ?GameTeam
    {
        return $this->teams->firstWhere('outcome', Outcome::WIN());
    }

    public function getLoserAttribute(): ?GameTeam
    {
        return $this->teams->firstWhere('outcome', Outcome::LOSS());
    }

    public function getScoreAttribute(): string
    {
        if ($this->winner === null || $this->loser === null) {
            return '?';
        }

        return $this->winner->final_score.'-'.$this->loser->final_score;
    }

    public static function fromDotApi(array $payload): ?self
    {
        $gameId = Arr::get($payload, 'id');
        $map = Map::fromDotApi(Arr::get($payload, 'details.map'));
        $gamevariant = Gamevariant::fromDotApi(Arr::get($payload, 'details.ugcgamevariant'));

        // Customs do not have a Playlist
        $playlistData = Arr::get($payload, 'details.playlist');
        if ($playlistData) {
            $playlist = Playlist::fromPlaylistId(Arr::get($playlistData, 'id'));
        }

        /** @var Mode|null $mode */
        $mode = Mode::coerce(Arr::get($payload, 'properties.type'));

        /** @var Game $game */
        $game = self::query()
            ->where('uuid', $gameId)
            ->firstOrNew([
                'uuid' => $gameId,
            ]);

        $game->category()->associate(null);
        $game->map()->associate($map);
        $game->gamevariant()->associate($gamevariant);
        if (isset($playlist)) {
            $game->playlist()->associate($playlist);
        }

        $game->is_ffa = false;
        if (Arr::has($payload, 'teams')) {
            $game->is_ffa = count(Arr::get($payload, 'teams', [])) === 0;
        }

        $game->is_lan ??= $mode && $mode->is(Mode::LAN());
        $game->experience = Arr::get($payload, 'properties.experience');
        $game->occurred_at = Arr::get($payload, 'started_at');
        $game->duration_seconds = Arr::get($payload, 'playable_duration.seconds');
        $game->season_number = Arr::get($payload, 'season.id');
        $game->season_version = Arr::get($payload, 'season.version');
        $game->version = config('services.dotapi.version');

        if (Arr::has($payload, 'players.0.name')) {
            $game->was_pulled = true;
        }

        if ($game->isDirty()) {
            $game->save();
        }

        if (Arr::has($payload, 'teams.0.id')) {
            foreach (Arr::get($payload, 'teams', []) as $teamData) {
                $teamData['_leaf']['game'] = $game;
                GameTeam::fromDotApi($teamData);
            }
        }

        if (Arr::has($payload, 'players')) {
            foreach (Arr::get($payload, 'players', []) as $playerData) {
                $gamertag = Arr::get($playerData, 'name');
                $type = Arr::get($playerData, 'properties.type', PlayerType::PLAYER);

                // Skip unresolved users from upstream API. We will force the game not yet updated to
                // re-process later.
                if ($type === PlayerType::PLAYER && (bool) Arr::get($playerData, 'attributes.resolved') === false) {
                    $game->was_pulled = false;
                    $game->save();

                    continue;
                }

                $player = Player::fromGamertag($gamertag);
                if (! $player->exists) {
                    $player->is_bot = $type === PlayerType::BOT;
                    $player->save();
                    PullAppearance::dispatch($player);
                }
                $playerData['_leaf']['player'] = $player;
                $playerData['_leaf']['game'] = $game;
                GamePlayer::fromDotApi($playerData);
            }
        }

        return $game;
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo<Map, $this>
     */
    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }

    /**
     * @return BelongsTo<Gamevariant, $this>
     */
    public function gamevariant(): BelongsTo
    {
        return $this->belongsTo(Gamevariant::class);
    }

    /**
     * @return BelongsTo<Playlist, $this>
     */
    public function playlist(): BelongsTo
    {
        return $this->belongsTo(Playlist::class);
    }

    /**
     * @return HasMany<GamePlayer, $this>
     */
    public function players(): HasMany
    {
        return $this->hasMany(GamePlayer::class);
    }

    /**
     * @return HasMany<GameTeam, $this>
     */
    public function teams(): HasMany
    {
        return $this->hasMany(GameTeam::class)
            ->orderBy('rank');
    }

    /**
     * @return HasMany<Analytic, $this>
     */
    public function analytics(): HasMany
    {
        return $this->hasMany(Analytic::class);
    }
}

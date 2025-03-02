<?php

namespace App\Models;

use App\Enums\CompetitiveMode;
use App\Enums\PlayerTab;
use App\Jobs\PullAppearance;
use App\Jobs\PullCompetitive;
use App\Jobs\PullMatchHistory;
use App\Jobs\PullServiceRecord;
use App\Models\Contracts\HasDotApi;
use App\Models\Pivots\MatchupPlayer;
use App\Models\Pivots\PersonalResult;
use App\Observers\PlayerObserver;
use App\Services\DotApi\Enums\Mode;
use App\Services\DotApi\InfiniteInterface;
use App\Support\Image\ImageHelper;
use App\Support\Session\SeasonSession;
use Carbon\Carbon;
use Database\Factories\PlayerFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;

/**
 * @property int $id
 * @property int|null $rank_id
 * @property int|null $next_rank_id
 * @property string|null $xuid
 * @property string $gamertag
 * @property int|null $xp
 * @property string $service_tag
 * @property bool $is_private
 * @property bool $is_donator
 * @property bool $is_bot
 * @property bool $is_cheater
 * @property bool $is_botfarmer
 * @property bool $is_forced_farmer
 * @property int|null $last_game_id_pulled
 * @property int|null $last_custom_game_id_pulled
 * @property int|null $last_lan_game_id_pulled
 * @property string $last_csr_key
 * @property string $emblem_url
 * @property string $backdrop_url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Rank|null $rank
 * @property-read Rank|null $nextRank
 * @property-read float $percentage_next_rank
 * @property-read string $percentage_next_rank_color
 * @property-read int $xp_towards_next_rank
 * @property-read int $xp_required_for_next_rank
 * @property-read string $percent_progress_to_hero
 * @property-read Collection<int, Game> $games
 * @property-read Collection<int, Csr> $csrs
 * @property-read Collection<int, MatchupPlayer> $faceitPlayers
 * @property-read Collection<int, PlayerBan> $bans
 * @property-read Collection<int, MedalAnalytic> $medals
 * @property-read Collection<int, Analytic> $analytics
 * @property-read PlayerBan|null $latestBan
 * @property-read ServiceRecord $serviceRecord
 * @property-read ServiceRecord $serviceRecordPvp
 * @property-read string $url_safe_gamertag
 *
 * @method static PlayerFactory factory(...$parameters)
 */
#[ObservedBy(PlayerObserver::class)]
class Player extends Model implements HasDotApi, Sitemapable
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public $with = [
        'rank',
    ];

    public $casts = [
        'is_donator' => 'bool',
        'is_botfarmer' => 'bool',
        'is_forced_farmer' => 'bool',
    ];

    public function getRouteKeyName(): string
    {
        return 'gamertag';
    }

    public function getUrlSafeGamertagAttribute(): string
    {
        return urlencode($this->gamertag);
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $gamertag = urldecode($value);

        try {
            return $this->query()
                ->where('gamertag', $gamertag)
                ->firstOrFail();
        } catch (ModelNotFoundException) {
            /** @var InfiniteInterface $client */
            $client = resolve(InfiniteInterface::class);
            $client->appearance($gamertag);

            return $this->query()
                ->where('gamertag', $gamertag)
                ->first();
        }
    }

    public function getEmblemUrlAttribute(?string $value): ?string
    {
        $filename = ImageHelper::getInternalFilenameFromAutocode($value);

        if ($filename && File::exists(public_path('storage/images/emblems/'.$filename))) {
            return asset('storage/images/emblems/'.$filename);
        }

        return $value;
    }

    public function getBackdropUrlAttribute(?string $value): ?string
    {
        $filename = ImageHelper::getInternalFilenameFromAutocode($value);

        if ($filename && File::exists(public_path('storage/images/backdrops/'.$filename))) {
            return asset('storage/images/backdrops/'.$filename);
        }

        return $value;
    }

    public function getPercentageNextRankAttribute(): float
    {
        $lastThreshold = $this->rank?->threshold;
        $threshold = $this->nextRank?->threshold;

        if ($threshold && $lastThreshold) {
            $xpTowardsNext = $this->xp_towards_next_rank;

            return (float) number_format(($xpTowardsNext / ($this->nextRank->required ?? 1)) * 100, 2);
        }

        return 100.0;
    }

    public function getXpTowardsNextRankAttribute(): int
    {
        $lastThreshold = $this->rank?->threshold;
        $threshold = $this->nextRank?->threshold;

        if ($threshold && $lastThreshold) {
            return $this->xp - $lastThreshold;
        }

        return 100;
    }

    public function getXpRequiredForNextRankAttribute(): int
    {
        return $this->nextRank->required ?? 100;
    }

    public function getPercentProgressToHeroAttribute(): string
    {
        return number_format(($this->xp / 9_319_350) * 100, 2);
    }

    public function getPercentageNextRankColorAttribute(): string
    {
        return match (true) {
            $this->percentage_next_rank > 80 => 'is-success',
            $this->percentage_next_rank > 60 && $this->percentage_next_rank <= 80 => 'is-primary',
            $this->percentage_next_rank > 40 && $this->percentage_next_rank <= 60 => 'is-warning',
            default => 'is-danger',
        };
    }

    public static function fromGamertag(string $gamertag): self
    {
        return self::query()
            ->where('gamertag', $gamertag)
            ->firstOrNew([
                'gamertag' => $gamertag,
            ]);
    }

    public static function fromDotApi(array $payload): ?self
    {
        $isRankPayload = Arr::has($payload, 'data.current.rank');
        $player = self::fromGamertag(Arr::get($payload, 'additional.params.gamertag'));

        if ($isRankPayload) {
            $player->rank_id = (int) Arr::get($payload, 'data.current.rank');
            $player->next_rank_id = Arr::get($payload, 'data.next.rank');
            $player->xp = max(0, (int) Arr::get($payload, 'data.level.total_xp'));
        } else {
            $player->service_tag = Arr::get($payload, 'data.service_tag');
            $player->emblem_url = Arr::get($payload, 'data.image_urls.emblem');
            $player->backdrop_url = Arr::get($payload, 'data.image_urls.backdrop');
        }

        if ($player->isDirty()) {
            $player->save();
        }

        return $player;
    }

    public function syncXuidFromXboxApi(): void
    {
        /** @var InfiniteInterface $client */
        $client = resolve(InfiniteInterface::class);
        $this->xuid = $client->xuid($this->url_safe_gamertag);
    }

    public function checkForBanFromDotApi(): bool
    {
        /** @var InfiniteInterface $client */
        $client = resolve(InfiniteInterface::class);

        $bans = $client->banSummary($this);
        if ($bans->isEmpty()) {
            return false;
        }

        $this->is_cheater = true;
        $this->save();

        return $this->is_cheater;
    }

    public function updateFromDotApi(bool $forceUpdate = false, ?string $type = null): void
    {
        $seasonModel = SeasonSession::model();

        /** @var InfiniteInterface $client */
        $client = resolve(InfiniteInterface::class);

        PullAppearance::dispatch($this);

        // Only pull LAN events for those who have a linked HCS profile.
        if ($this->faceitPlayers->count() > 0 && $type !== PlayerTab::LAN) {
            PullMatchHistory::dispatch($this, Mode::LAN());
        }

        if ($type === PlayerTab::OVERVIEW) {
            PullCompetitive::dispatch($this, $seasonModel->csr_key);
            PullMatchHistory::dispatch($this, Mode::MATCHMADE());
            PullMatchHistory::dispatch($this, Mode::CUSTOM());
            $client->serviceRecord($this, $seasonModel->identifier);
        } elseif ($type === PlayerTab::COMPETITIVE) {
            PullMatchHistory::dispatch($this, Mode::MATCHMADE());
            PullMatchHistory::dispatch($this, Mode::CUSTOM());
            PullServiceRecord::dispatch($this, $seasonModel->identifier);
            $client->competitive($this, $seasonModel->csr_key);
        } elseif (in_array($type, [PlayerTab::MATCHES, PlayerTab::MODES])) {
            PullCompetitive::dispatch($this, $seasonModel->csr_key);
            PullMatchHistory::dispatch($this, Mode::CUSTOM());
            PullServiceRecord::dispatch($this, $seasonModel->identifier);
            $client->matches($this, Mode::MATCHMADE(), $forceUpdate);
        } elseif ($type === PlayerTab::CUSTOM) {
            PullCompetitive::dispatch($this, $seasonModel->csr_key);
            PullMatchHistory::dispatch($this, Mode::MATCHMADE());
            PullServiceRecord::dispatch($this, $seasonModel->identifier);
            $client->matches($this, Mode::CUSTOM(), $forceUpdate);
        } elseif ($type === PlayerTab::LAN) {
            PullCompetitive::dispatch($this, $seasonModel->csr_key);
            PullMatchHistory::dispatch($this, Mode::MATCHMADE());
            PullMatchHistory::dispatch($this, Mode::CUSTOM());
            PullServiceRecord::dispatch($this, $seasonModel->identifier);
            $client->matches($this, Mode::LAN(), $forceUpdate);
        }

        $client->careerRank($this);

        // We will ignore Firefight (+ others) from this calculation as those medals are ignored in the Service Record.
        // Thus including these matches would skew the data in detection of a "Bot Farmer".
        $skippedPlaylistUuids = [
            config('services.halo.playlists.survive-the-dead'),
            config('services.halo.playlists.firefight-koth'),
            config('services.halo.playlists.firefight-heroic'),
            config('services.halo.playlists.firefight-legendary'),
            config('services.halo.playlists.firefight-grunt-koth'),
            config('services.halo.playlists.firefight-grunt-heroic'),
            config('services.halo.playlists.firefight-grunt-legendary'),
            config('services.halo.playlists.firefight-composer-normal'),
            config('services.halo.playlists.firefight-composer-heroic'),
            config('services.halo.playlists.firefight-composer-legendary'),
            config('services.halo.playlists.firefight-battle-for-reach'),
            config('services.halo.playlists.firefight-3person'),
            config('services.halo.playlists.firefight-fiesta'),
        ];

        $firefightIds = Playlist::query()
            ->select('id')
            ->whereIn('uuid', $skippedPlaylistUuids)
            ->get()
            ->pluck('id');

        $botBootcampId = (int) Playlist::query()
            ->select('id')
            ->where('uuid', config('services.halo.playlists.bot-bootcamp'))
            ->value('id');

        // Check for "Bot Farmer" status - aka a ton of Bot Bootcamp
        $playlistBreakdown = DB::query()
            ->from('game_players')
            ->select('games.playlist_id', new Expression('COUNT(game_players.id) as total'))
            ->where('player_id', $this->id)
            ->whereNotNull('games.playlist_id')
            ->join('games', 'game_players.game_id', '=', 'games.id')
            ->groupBy('games.playlist_id')
            ->get();

        // We now filter out skipped playlists from the breakdown.
        $playlistBreakdown = $playlistBreakdown->filter(function (\stdClass $row) use ($firefightIds) {
            return ! $firefightIds->contains($row->playlist_id);
        });

        $totalGames = $playlistBreakdown->sum('total');
        if ($totalGames >= 100 && ! $this->is_forced_farmer) {
            $botBootcampPercent = $playlistBreakdown->firstWhere('playlist_id', $botBootcampId)?->total / $totalGames;
            $playsTooMuchBotBootcamp = $botBootcampPercent >= config('services.halo.botfarmer_threshold');
            $this->is_botfarmer = $playsTooMuchBotBootcamp;
        }
    }

    public function currentRanked(?string $seasonKey = null, bool $isCurrentOrAll = true): Collection
    {
        $query = $this->csrs()
            ->where('season_key', $seasonKey)
            ->where('mode', CompetitiveMode::CURRENT)
            ->orderByDesc('csr');

        // If a previous season (or all) - don't show categories that never left placements as no CSR.
        if (! $isCurrentOrAll) {
            $query->where('csr', '>', 0);
        }

        return $query->get();
    }

    public function seasonHighRanked(?string $seasonKey = null): Collection
    {
        return $this->csrs()
            ->where('season_key', $seasonKey)
            ->where('mode', CompetitiveMode::SEASON)
            ->orderByDesc('csr')
            ->get();
    }

    public function allTimeRanked(): Collection
    {
        return $this->csrs()
            ->whereNull('season_key')
            ->orderByDesc('csr')
            ->get();
    }

    public function toSitemapTag(): Url|string|array
    {
        $url = new Url(route('player', $this));
        $url->setLastModificationDate($this->updated_at);
        $url->setChangeFrequency('always');

        return $url;
    }

    /**
     * @return BelongsTo<Rank, $this>
     */
    public function rank(): BelongsTo
    {
        return $this->belongsTo(Rank::class, 'rank_id');
    }

    /**
     * @return BelongsTo<Rank, $this>
     */
    public function nextRank(): BelongsTo
    {
        return $this->belongsTo(Rank::class, 'next_rank_id');
    }

    /**
     * @return HasOne<ServiceRecord, $this>
     */
    public function serviceRecord(): HasOne
    {
        return $this->hasOne(ServiceRecord::class)
            ->where('mode', \App\Enums\Mode::MATCHMADE_RANKED);
    }

    /**
     * @return HasOne<ServiceRecord, $this>
     */
    public function serviceRecordPvp(): HasOne
    {
        return $this->hasOne(ServiceRecord::class)
            ->where('mode', \App\Enums\Mode::MATCHMADE_PVP);
    }

    /**
     * @return HasMany<Csr, $this>
     */
    public function csrs(): HasMany
    {
        return $this->hasMany(Csr::class);
    }

    /**
     * @return HasMany<MatchupPlayer, $this>
     */
    public function faceitPlayers(): HasMany
    {
        return $this->hasMany(MatchupPlayer::class);
    }

    /**
     * @return HasMany<PlayerBan, $this>
     */
    public function bans(): HasMany
    {
        return $this->hasMany(PlayerBan::class);
    }

    /**
     * @return HasOne<PlayerBan, $this>
     */
    public function latestBan(): HasOne
    {
        return $this->hasOne(PlayerBan::class)->latestOfMany();
    }

    /**
     * @return HasMany<MedalAnalytic, $this>
     */
    public function medals(): HasMany
    {
        return $this->hasMany(MedalAnalytic::class);
    }

    /**
     * @return HasMany<Analytic, $this>
     */
    public function analytics(): HasMany
    {
        return $this->hasMany(Analytic::class);
    }

    /**
     * @return BelongsToMany<Game, $this>
     */
    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_players')
            ->as('personal')
            ->using(PersonalResult::class)
            ->withPivot([
                'outcome',
                'kills',
                'deaths',
                'assists',
                'kd',
                'kda',
                'accuracy',
                'score',
                'mmr',
                'rank',
                'pre_csr',
                'post_csr',
                'matches_remaining',
            ]);
    }
}

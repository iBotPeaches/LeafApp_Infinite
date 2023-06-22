<?php

namespace App\Models;

use App\Enums\CompetitiveMode;
use App\Enums\PlayerTab;
use App\Jobs\PullAppearance;
use App\Jobs\PullCompetitive;
use App\Jobs\PullMatchHistory;
use App\Jobs\PullServiceRecord;
use App\Models\Contracts\HasHaloDotApi;
use App\Models\Pivots\MatchupPlayer;
use App\Models\Pivots\PersonalResult;
use App\Services\HaloDotApi\Enums\Mode;
use App\Services\HaloDotApi\InfiniteInterface;
use App\Support\Image\ImageHelper;
use App\Support\Session\SeasonSession;
use Carbon\Carbon;
use Database\Factories\PlayerFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;
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
 * @property bool $is_bot
 * @property bool $is_cheater
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
 * @property-read Collection<int, Game> $games
 * @property-read Collection<int, Csr> $csrs
 * @property-read Collection<int, MatchupPlayer> $faceitPlayers
 * @property-read Collection<int, PlayerBan> $bans
 * @property-read ServiceRecord $serviceRecord
 * @property-read ServiceRecord $serviceRecordPvp
 * @property-read string $url_safe_gamertag
 *
 * @method static PlayerFactory factory(...$parameters)
 */
class Player extends Model implements HasHaloDotApi, Sitemapable
{
    use HasFactory;

    public $guarded = [
        'id',
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
        $threshold = $this->rank?->threshold;

        if ($threshold) {
            return (float) number_format((($this->xp / $threshold) * 100));
        }

        return 100.0;
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
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return self::query()
            ->where('gamertag', $gamertag)
            ->firstOrNew([
                'gamertag' => $gamertag,
            ]);
    }

    public static function fromHaloDotApi(array $payload): ?self
    {
        $isRankPayload = Arr::has($payload, 'data.current.rank');
        $player = self::fromGamertag(Arr::get($payload, 'additional.params.gamertag'));

        if ($isRankPayload) {
            $player->rank_id = (int) Arr::get($payload, 'data.current.rank');
            $player->next_rank_id = (int) Arr::get($payload, 'data.next.rank');
            $player->xp = (int) Arr::get($payload, 'data.current.progression');
        } else {
            $player->service_tag = Arr::get($payload, 'data.service_tag');
            $player->emblem_url = Arr::get($payload, 'data.image_urls.emblem');
            $player->backdrop_url = Arr::get($payload, 'data.image_urls.backdrop');
        }

        if ($player->isDirty()) {
            $player->saveOrFail();
        }

        return $player;
    }

    public function syncXuidFromXboxApi(): void
    {
        /** @var InfiniteInterface $client */
        $client = resolve(InfiniteInterface::class);
        $this->xuid = $client->xuid($this->url_safe_gamertag);
    }

    public function updateFromHaloDotApi(bool $forceUpdate = false, ?string $type = null): void
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

    public function rank(): BelongsTo
    {
        return $this->belongsTo(Rank::class, 'rank_id');
    }

    public function nextRank(): BelongsTo
    {
        return $this->belongsTo(Rank::class, 'next_rank_id');
    }

    public function serviceRecord(): HasOne
    {
        return $this->hasOne(ServiceRecord::class)
            ->where('mode', \App\Enums\Mode::MATCHMADE_RANKED);
    }

    public function serviceRecordPvp(): HasOne
    {
        return $this->hasOne(ServiceRecord::class)
            ->where('mode', \App\Enums\Mode::MATCHMADE_PVP);
    }

    public function csrs(): HasMany
    {
        return $this->hasMany(Csr::class);
    }

    public function faceitPlayers(): HasMany
    {
        return $this->hasMany(MatchupPlayer::class);
    }

    public function bans(): HasMany
    {
        return $this->hasMany(PlayerBan::class);
    }

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

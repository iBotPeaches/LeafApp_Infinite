<?php

namespace App\Models;

use App\Enums\CompetitiveMode;
use App\Enums\PlayerTab;
use App\Jobs\PullAppearance;
use App\Jobs\PullCompetitive;
use App\Jobs\PullMatchHistory;
use App\Jobs\PullMmr;
use App\Jobs\PullServiceRecord;
use App\Models\Contracts\HasHaloDotApi;
use App\Models\Pivots\MatchupPlayer;
use App\Models\Pivots\PersonalResult;
use App\Services\Autocode\Enums\Filter;
use App\Services\Autocode\Enums\Mode;
use App\Services\Autocode\InfiniteInterface;
use App\Services\XboxApi\XboxInterface;
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

/**
 * @property int $id
 * @property string|null $xuid
 * @property string $gamertag
 * @property string $service_tag
 * @property boolean $is_private
 * @property int|null $last_game_id_pulled
 * @property int|null $last_custom_game_id_pulled
 * @property int|null $last_lan_game_id_pulled
 * @property string $last_csr_key
 * @property float|null $mmr
 * @property int $mmr_game_id
 * @property string $emblem_url
 * @property string $backdrop_url
 * @property-read Collection<int, Game> $games
 * @property-read Collection<int, Csr> $csrs
 * @property-read Collection<int, MatchupPlayer> $faceitPlayers
 * @property-read Game|null $mmrGame
 * @property-read ServiceRecord $serviceRecord
 * @property-read ServiceRecord $serviceRecordPvp
 * @method static PlayerFactory factory(...$parameters)
 */
class Player extends Model implements HasHaloDotApi
{
    use HasFactory;

    public $guarded = [
        'id'
    ];

    public function getRouteKeyName(): string
    {
        return 'gamertag';
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

    public static function fromGamertag(string $gamertag): self
    {
        return self::query()
            ->where('gamertag', $gamertag)
            ->firstOrNew([
                'gamertag' => $gamertag
            ]);
    }

    public static function fromHaloDotApi(array $payload): ?self
    {
        $player = self::fromGamertag(Arr::get($payload, 'additional.parameters.gamertag'));

        $player->service_tag = Arr::get($payload, 'data.service_tag');
        $player->emblem_url = Arr::get($payload, 'data.emblem_url');
        $player->backdrop_url = Arr::get($payload, 'data.backdrop_image_url');

        if ($player->isDirty()) {
            $player->saveOrFail();
        }

        return $player;
    }

    public function syncXuidFromXboxApi(): void
    {
        /** @var XboxInterface $client */
        $client = resolve(XboxInterface::class);
        $this->xuid = $client->xuid($this->gamertag);
    }

    public function updateFromHaloDotApi(bool $forceUpdate = false, ?string $type = null): void
    {
        $seasonNumber = (int)config('services.autocode.competitive.season');

        /** @var InfiniteInterface $client */
        $client = resolve(InfiniteInterface::class);

        PullAppearance::dispatch($this);

        // Only pull LAN events for those who have a linked HCS profile.
        if ($this->faceitPlayers->count() > 0 && $type !== PlayerTab::LAN) {
            PullMatchHistory::dispatch($this, Mode::LAN());
        }

        if ($type === PlayerTab::OVERVIEW) {
            PullCompetitive::dispatch($this);
            PullMatchHistory::dispatch($this, Mode::MATCHMADE());
            PullMatchHistory::dispatch($this, Mode::CUSTOM());
            PullMmr::dispatch($this);
            $client->serviceRecord($this);
        } elseif ($type === PlayerTab::COMPETITIVE) {
            PullMatchHistory::dispatch($this, Mode::MATCHMADE());
            PullMatchHistory::dispatch($this, Mode::CUSTOM());
            PullServiceRecord::dispatch($this);
            $client->competitive($this, $seasonNumber);
            $client->mmr($this);
        } elseif ($type === PlayerTab::MATCHES) {
            PullCompetitive::dispatch($this);
            PullMatchHistory::dispatch($this, Mode::CUSTOM());
            PullMmr::dispatch($this);
            PullServiceRecord::dispatch($this);
            $client->matches($this, Mode::MATCHMADE(), $forceUpdate);
        } elseif ($type === PlayerTab::CUSTOM) {
            PullCompetitive::dispatch($this);
            PullMatchHistory::dispatch($this, Mode::MATCHMADE());
            PullMmr::dispatch($this);
            PullServiceRecord::dispatch($this);
            $client->matches($this, Mode::CUSTOM(), $forceUpdate);
        } elseif ($type === PlayerTab::LAN) {
            PullCompetitive::dispatch($this);
            PullMatchHistory::dispatch($this, Mode::MATCHMADE());
            PullMatchHistory::dispatch($this, Mode::CUSTOM());
            PullMmr::dispatch($this);
            PullServiceRecord::dispatch($this);
            $client->matches($this, Mode::LAN(), $forceUpdate);
        }
    }

    public function currentRanked(int $season = 1): Collection
    {
        return $this->csrs()
            ->where('season', $season)
            ->where('mode', CompetitiveMode::CURRENT)
            ->orderByDesc('csr')
            ->get();
    }

    public function seasonHighRanked(int $season = 1): Collection
    {
        return $this->csrs()
            ->where('season', $season)
            ->where('mode', CompetitiveMode::SEASON)
            ->orderByDesc('csr')
            ->get();
    }

    public function allTimeRanked(): Collection
    {
        return $this->csrs()
            ->whereNull('season')
            ->orderByDesc('csr')
            ->get();
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

    public function mmrGame(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'mmr_game_id');
    }

    public function csrs(): HasMany
    {
        return $this->hasMany(Csr::class);
    }

    public function faceitPlayers(): HasMany
    {
        return $this->hasMany(MatchupPlayer::class);
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
            ]);
    }
}

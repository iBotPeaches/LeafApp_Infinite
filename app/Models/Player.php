<?php

namespace App\Models;

use App\Enums\PlayerTab;
use App\Jobs\PullAppearance;
use App\Jobs\PullMatchHistory;
use App\Models\Contracts\HasHaloDotApi;
use App\Models\Pivots\PersonalResult;
use App\Services\Autocode\Enums\Mode;
use App\Services\Autocode\InfiniteInterface;
use App\Services\XboxApi\XboxInterface;
use Database\Factories\PlayerFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
 * @property string $emblem_url
 * @property string $backdrop_url
 * @property-read Game[]|Collection $games
 * @property-read Csr[]|Collection $csrs
 * @property-read ServiceRecord $serviceRecord
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
        return $this->query()
            ->where('gamertag', urldecode($value))
            ->firstOrFail();
    }

    public static function fromGamertag(string $gamertag): self
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return self::query()
            ->where('gamertag', $gamertag)
            ->firstOrNew([
                'gamertag' => $gamertag
            ]);
    }

    public static function fromHaloDotApi(array $payload): ?self
    {
        $player = self::fromGamertag(Arr::get($payload, 'additional.gamertag'));

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
        /** @var InfiniteInterface $client */
        $client = resolve(InfiniteInterface::class);
        $client->competitive($this);

        if (in_array($type, [PlayerTab::OVERVIEW, PlayerTab::COMPETITIVE])) {
            PullMatchHistory::dispatch($this, Mode::MATCHMADE());
            PullMatchHistory::dispatch($this, Mode::CUSTOM());
        } elseif ($type === PlayerTab::MATCHES) {
            $client->matches($this, Mode::MATCHMADE(), $forceUpdate);
            PullMatchHistory::dispatch($this, Mode::CUSTOM());
        } elseif ($type === PlayerTab::CUSTOM) {
            $client->matches($this, Mode::CUSTOM(), $forceUpdate);
            PullMatchHistory::dispatch($this, Mode::MATCHMADE());
        }

        $client->serviceRecord($this);

        // Dispatch an async update for the appearance
        PullAppearance::dispatch($this);
    }

    public function ranked(int $season = 1): Collection
    {
        return $this->csrs()
            ->where('season', $season)
            ->orderByDesc('csr')
            ->get();
    }

    public function serviceRecord(): HasOne
    {
        return $this->hasOne(ServiceRecord::class);
    }

    public function csrs(): HasMany
    {
        return $this->hasMany(Csr::class);
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
                'rank',
                'pre_csr',
                'post_csr',
            ]);
    }
}

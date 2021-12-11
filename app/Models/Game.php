<?php

namespace App\Models;

use App\Enums\Experience;
use App\Models\Contracts\HasHaloDotApi;
use App\Models\Pivots\PersonalResult;
use App\Services\Autocode\Enums\PlayerType;
use App\Services\Autocode\InfiniteInterface;
use Carbon\Carbon;
use Database\Factories\GameFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property string $uuid
 * @property int $category_id
 * @property int $map_id
 * @property boolean $is_ranked
 * @property boolean $is_ffa
 * @property boolean $is_scored
 * @property Experience $experience
 * @property Carbon $occurred_at
 * @property int $duration_seconds
 * @property-read Category $category
 * @property-read Map $map
 * @property-read PersonalResult $personal
 * @property-read GamePlayer[]|Collection $players
 * @property-read GameTeam[]|Collection $teams
 * @method static GameFactory factory(...$parameters)
 */
class Game extends Model implements HasHaloDotApi
{
    use HasFactory;

    public $guarded = [
        'id',
        'category_id',
        'map_id'
    ];

    public $dates = [
        'occurred_at'
    ];

    public $casts = [
        'experience' => Experience::class
    ];

    public $with = [
        'category',
        'map'
    ];

    public $timestamps = false;

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function setExperienceAttribute(string $value): void
    {
        $experience = is_numeric($value) ? Experience::fromValue((int) $value) : Experience::coerce($value);
        if (empty($experience)) {
            throw new \InvalidArgumentException('Invalid Experience Enum (' . $value . ')');
        }

        $this->attributes['experience'] = $experience->value;
    }

    public function updateFromHaloDotApi(): void
    {
        /** @var InfiniteInterface $client */
        $client = resolve(InfiniteInterface::class);

        $client->match($this->uuid);
    }

    public function findTeamFromId(int $id): ?GameTeam
    {
        return $this->teams->firstWhere('internal_team_id', $id);
    }

    public static function fromHaloDotApi(array $payload): ?self
    {
        $gameId = Arr::get($payload, 'id');
        $category = Category::fromHaloDotApi(Arr::get($payload, 'details.category'));
        $map = Map::fromHaloDotApi(Arr::get($payload, 'details.map'));

        /** @var Game $game */
        $game = self::query()
            ->where('uuid', $gameId)
            ->firstOrNew([
                'uuid' => $gameId
            ]);

        $game->category()->associate($category);
        $game->map()->associate($map);
        $game->is_ranked = (bool) Arr::get($payload, 'ranked');
        $game->is_ffa = !(bool) Arr::get($payload, 'teams.enabled');
        $game->is_scored = (bool) Arr::get($payload, 'teams.scoring');
        $game->experience = Arr::get($payload, 'experience');
        $game->occurred_at = Arr::get($payload, 'played_at');
        $game->duration_seconds = Arr::get($payload, 'duration.seconds');

        if ($game->isDirty()) {
            $game->saveOrFail();
        }

        if (Arr::has($payload, 'teams.details')) {
            foreach (Arr::get($payload, 'teams.details', []) as $teamData) {
                $teamData['_leaf']['game'] = $game;
                GameTeam::fromHaloDotApi($teamData);
            }
        }

        if (Arr::has($payload, 'players')) {
            foreach (Arr::get($payload, 'players', []) as $playerData) {
                // TODO - No idea if non-players are in games.
                if (Arr::get($playerData, 'type', PlayerType::PLAYER) !== PlayerType::PLAYER) {
                    continue;
                }

                $player = Player::fromGamertag(Arr::get($playerData, 'gamertag'));
                if (! $player->exists) {
                    $player->saveOrFail();
                }
                $playerData['_leaf']['player'] = $player;
                $playerData['_leaf']['game'] = $game;
                GamePlayer::fromHaloDotApi($playerData);
            }
        }

        return $game;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(GamePlayer::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(GameTeam::class)
            ->orderBy('rank');
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Outcome;
use App\Models\Contracts\HasHaloDotApi;
use App\Models\Traits\HasOutcome;
use Database\Factories\GameTeamFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property int $game_id
 * @property int $team_id
 * @property int $internal_team_id
 * @property Outcome $outcome
 * @property int $rank
 * @property int $score
 * @property float $mmr
 * @property float|null $winning_percent
 * @property int $final_score
 * @property-read Game $game
 * @property-read Team $team
 * @property-read GamePlayer[]|Collection<int, GamePlayer> $players
 * @property-read string $name
 * @property-read string $color
 * @property-read string $tooltip_color
 * @property-read float $csr
 *
 * @method static GameTeamFactory factory(...$parameters)
 */
class GameTeam extends Model implements HasHaloDotApi
{
    use HasFactory, HasOutcome;

    public $timestamps = false;

    public $casts = [
        'outcome' => Outcome::class,
    ];

    public $with = [
        'team',
    ];

    public function getColorAttribute(): string
    {
        return match ($this->internal_team_id) {
            0 => 'is-info',
            1 => 'is-danger',
            default => 'is-dark',
        };
    }

    public function getNameAttribute(): string
    {
        return $this->team->name;
    }

    public function getCsrAttribute(): float
    {
        return (float) $this->players->avg('pre_csr');
    }

    public function getEmblemUrlAttribute(): string
    {
        return asset('images/teams/'.$this->internal_team_id.'.png');
    }

    public function getTooltipColorAttribute(): string
    {
        return match ($this->internal_team_id) {
            0 => 'has-tooltip-info',
            1 => 'has-tooltip-danger',
            default => 'has-tooltip-dark',
        };
    }

    public static function fromHaloDotApi(array $payload): ?self
    {
        $internalTeamId = (int) Arr::get($payload, 'id');

        /** @var Game $game */
        $game = Arr::get($payload, '_leaf.game');

        /** @var GameTeam $gameTeam */
        $gameTeam = self::query()
            ->where('game_id', $game->id)
            ->where('internal_team_id', $internalTeamId)
            ->firstOrNew();

        $gameTeam->game()->associate($game);
        $gameTeam->internal_team_id = $internalTeamId;
        $gameTeam->outcome = Arr::get($payload, 'outcome');
        $gameTeam->rank = Arr::get($payload, 'rank');
        $gameTeam->mmr ??= Arr::get($payload, 'stats.mmr');
        $gameTeam->winning_percent ??= Arr::get($payload, 'odds.winning');
        $gameTeam->score ??= Arr::get($payload, 'stats.core.scores.personal');

        // We are going to check what type of category this is to extract the mode specific final value
        $key = match ($game->gamevariant?->category?->name) {
            'Oddball' => 'stats.core.rounds.won',
            default => 'stats.core.scores.points',
        };

        $gameTeam->final_score = Arr::get($payload, $key);

        if ($gameTeam->isDirty()) {
            $gameTeam->saveOrFail();
        }

        return $gameTeam;
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(GamePlayer::class);
    }
}

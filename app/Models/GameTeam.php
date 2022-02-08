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
 * @property int $internal_team_id
 * @property string $name
 * @property string $emblem_url
 * @property Outcome $outcome
 * @property int $rank
 * @property int $score
 * @property float $mmr
 * @property int $final_score
 * @property-read Game $game
 * @property-read GamePlayer[]|Collection $players
 * @property-read string $color
 * @property-read string $tooltip_color
 * @property-read float $csr
 * @method static GameTeamFactory factory(...$parameters)
 */
class GameTeam extends Model implements HasHaloDotApi
{
    use HasFactory, HasOutcome;

    public $timestamps = false;

    public $casts = [
        'outcome' => Outcome::class
    ];

    public function getColorAttribute(): string
    {
        return match ($this->name) {
            'Eagle' => 'is-info',
            'Cobra' => 'is-danger',
            default => 'is-dark',
        };
    }

    public function getCsrAttribute(): float
    {
        return (float)$this->players->avg('pre_csr');
    }

    public function getTooltipColorAttribute(): string
    {
        return match ($this->name) {
            'Eagle' => 'has-tooltip-info',
            'Cobra' => 'has-tooltip-danger',
            default => 'has-tooltip-dark',
        };
    }

    public static function fromHaloDotApi(array $payload): ?self
    {
        $internalTeamId = (int)Arr::get($payload, 'team.id');

        /** @var Game $game */
        $game = Arr::get($payload, '_leaf.game');

        /** @var GameTeam $gameTeam */
        $gameTeam = self::query()
            ->where('game_id', $game->id)
            ->where('internal_team_id', $internalTeamId)
            ->firstOrNew();

        $gameTeam->game()->associate($game);
        $gameTeam->internal_team_id = $internalTeamId;
        $gameTeam->name = Arr::get($payload, 'team.name');
        $gameTeam->emblem_url = Arr::get($payload, 'team.emblem_url');
        $gameTeam->outcome = Arr::get($payload, 'outcome');
        $gameTeam->rank = Arr::get($payload, 'rank');
        $gameTeam->mmr = Arr::get($payload, 'team.skill.mmr');
        $gameTeam->score = Arr::get($payload, 'stats.core.score');

        // We are going to check what type of category this is to extract the mode specific final value
        $key = match ($game->category->name) {
            'Oddball' => 'stats.core.rounds.won',
            default => 'stats.core.points',
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

    public function players(): HasMany
    {
        return $this->hasMany(GamePlayer::class);
    }
}

<?php

namespace App\Models;

use App\Enums\CompetitiveMode;
use App\Enums\Input;
use App\Enums\Queue;
use App\Models\Contracts\HasDotApi;
use App\Models\Traits\HasPlaylist;
use App\Support\Csr\CsrHelper;
use Carbon\Carbon;
use Database\Factories\CsrFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * @property int $id
 * @property int $player_id
 * @property int $playlist_id
 * @property Queue|null $queue
 * @property Input|null $input
 * @property int|null $season
 * @property string|null $season_key
 * @property CompetitiveMode $mode
 * @property int $csr
 * @property int $matches_remaining
 * @property string $tier
 * @property int $tier_start_csr
 * @property int $sub_tier
 * @property string $next_tier
 * @property int $next_sub_tier
 * @property int $next_csr
 * @property int|null $champion_rank
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Player $player
 * @property-read Playlist $playlist
 * @property-read string $rank
 * @property-read string $next_rank
 * @property-read float $next_rank_percent
 * @property-read int $next_xp_for_level
 * @property-read int|null $current_xp_for_level
 *
 * @method static CsrFactory factory(...$parameters)
 */
class Csr extends Model implements HasDotApi
{
    use HasFactory, HasPlaylist;

    public $guarded = [
        'id',
    ];

    public $casts = [
        'csr' => 'int',
        'next_csr' => 'int',
        'input' => Input::class,
        'queue' => Queue::class,
        'mode' => CompetitiveMode::class,
    ];

    public $with = [
        'playlist',
    ];

    public $touches = [
        'player',
    ];

    public function setCsrAttribute(?int $csr): void
    {
        $this->attributes['csr'] = $csr === -1 ? null : $csr;
    }

    public function setModeAttribute(string $value): void
    {
        $mode = is_numeric($value) ? CompetitiveMode::fromValue((int) $value) : CompetitiveMode::coerce($value);

        $this->attributes['mode'] = $mode?->value;
    }

    public function getRankAttribute(): string
    {
        if ($this->champion_rank > 0) {
            return 'Champion';
        }

        if ($this->hasNextRank()) {
            return $this->tier.' '.($this->sub_tier + 1);
        }

        return $this->tier;
    }

    public function getNextRankAttribute(): string
    {
        if ($this->hasNextRank() && ! $this->isNextOnyx()) {
            return $this->next_tier.' '.($this->next_sub_tier + 1);
        }

        return $this->next_tier;
    }

    public function getNextXpForLevelAttribute(): int
    {
        return $this->next_csr - $this->tier_start_csr;
    }

    public function getCurrentXpForLevelAttribute(): ?int
    {
        if (empty($this->csr)) {
            return null;
        }

        return $this->csr - $this->tier_start_csr;
    }

    public function getNextRankPercentAttribute(): float
    {
        if ($this->next_xp_for_level === 0) {
            return 0;
        }

        return ($this->current_xp_for_level / $this->next_xp_for_level) * 100;
    }

    public function isOnyx(): bool
    {
        return $this->tier === 'Onyx';
    }

    public function isNextOnyx(): bool
    {
        return $this->next_tier === 'Onyx';
    }

    public function hasNextRank(): bool
    {
        if ($this->isOnyx()) {
            return false;
        }

        return $this->next_csr > 0;
    }

    public function hasPlacementsDone(): bool
    {
        return $this->csr !== null;
    }

    public function getRankPercentColor(): string
    {
        if ($this->next_csr === 0) {
            return 'is-dark';
        }

        return match (true) {
            $this->next_rank_percent > 80 => 'is-success',
            $this->next_rank_percent > 60 && $this->next_rank_percent <= 80 => 'is-primary',
            $this->next_rank_percent > 40 && $this->next_rank_percent <= 60 => 'is-warning',
            default => 'is-danger',
        };
    }

    public function getRankPercentTooltip(): string
    {
        if ($this->hasNextRank()) {
            return 'Up Next: '.$this->next_rank;
        }

        return $this->matches_remaining.' '.Str::plural('match', $this->matches_remaining).' remaining.';
    }

    public function toCsrObject(): \App\Support\Csr\Csr
    {
        return CsrHelper::getCsrFromValue($this->csr, $this->matches_remaining, $this->champion_rank);
    }

    public static function fromDotApi(array $payload): ?self
    {
        /** @var Player $player */
        $player = Arr::get($payload, 'player');
        $seasonCsr = (string) Arr::get($payload, 'additional.query.season_csr');
        $seasonModel = Season::ofSeasonIdentifierOrKey($seasonCsr);

        foreach (Arr::get($payload, 'data') as $playlist) {
            $queueName = Arr::get($playlist, 'properties.queue');
            $inputName = Arr::get($playlist, 'properties.input');

            $queue = Queue::coerce($queueName);
            $input = Input::coerce($inputName);

            $playlistModel = Playlist::fromPlaylistId((string) Arr::get($playlist, 'id'));

            if (empty($playlistModel)) {
                throw new InvalidArgumentException('Playlist not found.');
            }

            if (empty($queue) || empty($input)) {
                throw new \InvalidArgumentException(
                    'Queue ('.$queueName.') or input ('.$inputName.') is unknown.'
                );
            }

            foreach (Arr::get($playlist, 'response') as $key => $playlistMode) {
                $mode = CompetitiveMode::coerce($key);
                if (empty($mode)) {
                    throw new InvalidArgumentException('Mode ('.$key.') is unknown.');
                }

                $playlistSeason = $seasonModel;
                if ($mode->is(CompetitiveMode::ALL_TIME())) {
                    $playlistSeason = null;
                }

                /** @var Csr $csr */
                $csr = Csr::query()
                    ->where('player_id', $player->id)
                    ->where('playlist_id', $playlistModel->id)
                    ->where('season_key', $playlistSeason?->key)
                    ->where('mode', $mode->value)
                    ->where('queue', $queue->value)
                    ->where('input', $input->value)
                    ->firstOrNew();

                // Due to an older grunt.api issue(?) - the `season` parameter is nulled out during mid-season reset.
                // This keeps the value for allTime or season if the newer value is less.
                $newCsrValue = Arr::get($playlistMode, 'value');
                if ($mode->isNot(CompetitiveMode::CURRENT()) && $csr->csr > $newCsrValue) {
                    continue;
                }

                $csr->player()->associate($player);
                $csr->playlist()->associate($playlistModel);
                $csr->season_key = $playlistSeason?->key;
                $csr->mode = $mode;
                $csr->queue = $queue;
                $csr->input = $input;
                $csr->csr = $newCsrValue;
                $csr->matches_remaining = Arr::get($playlistMode, 'measurement_matches_remaining');

                // Subtracting 1 from `sub_tier` and `next_sub_tier` is because Autocode 0.3.8
                // Turned these from indexes to levels. This is more sane, but entire codebase expects indexes
                // Old data would become invalid and this is easier for now. For future, we should one-off migrate all
                // data in database to +1, then remove the -1 below.
                $csr->tier = Arr::get($playlistMode, 'tier');
                $csr->tier_start_csr = Arr::get($playlistMode, 'tier_start');
                $csr->sub_tier = ((int) Arr::get($playlistMode, 'sub_tier')) - 1;

                $csr->next_tier = Arr::get($playlistMode, 'next_tier');
                $csr->next_sub_tier = ((int) Arr::get($playlistMode, 'next_sub_tier')) - 1;
                $csr->next_csr = Arr::get($playlistMode, 'next_tier_start');

                /** @var array $extras */
                $extras = Arr::get($playlistMode, 'extra', []);
                $csr->champion_rank = CsrHelper::parseExtrasForChampionRank($extras);

                if ($csr->isDirty()) {
                    $csr->save();
                }
            }
        }

        return $csr ?? null;
    }

    /**
     * @return BelongsTo<Playlist, $this>
     */
    public function playlist(): BelongsTo
    {
        return $this->belongsTo(Playlist::class);
    }

    /**
     * @return BelongsTo<Player, $this>
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}

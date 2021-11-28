<?php

namespace App\Models;

use App\Enums\Input;
use App\Enums\Queue;
use App\Models\Contracts\HasHaloDotApi;
use Carbon\Carbon;
use Database\Factories\CsrFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property int $player_id
 * @property Queue $queue
 * @property Input $input
 * @property int $season
 * @property int $csr
 * @property int $matches_remaining
 * @property string $tier
 * @property int $tier_start_csr
 * @property string $tier_image_url
 * @property int $sub_tier
 * @property string $next_tier
 * @property int $next_sub_tier
 * @property int $next_csr
 * @property string $season_tier
 * @property int $season_sub_tier
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Player $player
 * @property-read string $rank
 * @property-read string $next_rank
 * @property-read float $next_rank_percent
 * @property-read float $next_xp_for_level
 * @property-read float $current_xp_for_level
 * @property-read string $title
 * @property-read string $icon
 * @method static CsrFactory factory(...$parameters)
 */
class Csr extends Model implements HasHaloDotApi
{
    use HasFactory;

    public $guarded = [
        'id'
    ];

    public $casts = [
        'csr' => 'int',
        'input' => Input::class,
        'queue' => Queue::class
    ];

    public function setCsrAttribute(int $csr): void
    {
        $this->attributes['csr'] = $csr === -1 ? null : $csr;
    }

    public function getRankAttribute(): string
    {
        if ($this->hasNextRank()) {
            return $this->tier . ' ' . ($this->sub_tier + 1);
        } else {
            return $this->tier;
        }
    }

    public function getNextRankAttribute(): string
    {
        if ($this->hasNextRank()) {
            return $this->next_tier . ' ' . ($this->next_sub_tier + 1);
        } else {
            return $this->next_tier;
        }
    }

    public function getNextXpForLevelAttribute(): float
    {
        return $this->next_csr - $this->tier_start_csr;
    }

    public function getCurrentXpForLevelAttribute(): float
    {
        return $this->next_csr - $this->csr;
    }

    public function getNextRankPercentAttribute(): float
    {
        if ($this->next_csr === 0) {
            return 0;
        }

        return ($this->current_xp_for_level / $this->next_xp_for_level) * 100;
    }

    public function getTitleAttribute(): string
    {
        return $this->queue->description;
    }

    public function getIconAttribute(): ? string
    {
        if ($this->queue->is(Queue::SOLO_DUO)) {
            return $this->input->is(Input::CONTROLLER())
                ? '<i class="fa fa-gamepad"></i>'
                : '<i class="fa fa-mouse"></i>';
        }

        return null;
    }

    public function isOnyx(): bool
    {
        return $this->tier === 'Onyx';
    }

    public function hasNextRank(): bool
    {
        if ($this->isOnyx()) {
            return false;
        }

        return $this->next_csr > 0;
    }

    public function getRankPercentColor(): string
    {
        if ($this->next_csr === 0) {
            return 'is-dark';
        }

        switch (true) {
            case $this->next_rank_percent > 80:
                return 'is-success';
            case $this->next_rank_percent > 60 && $this->next_rank_percent <= 80:
                return 'is-primary';
            case $this->next_rank_percent > 40 && $this->next_rank_percent <= 60:
                return 'is-warning';
            default:
                return 'is-danger';
        }
    }

    public static function fromHaloDotApi(array $payload): ?self
    {
        /** @var Player $player */
        $player = Arr::get($payload, 'player');
        $season = (int) Arr::get($payload, 'additional.season');

        foreach (Arr::get($payload, 'data') as $playlist) {
            $queueName = Arr::get($playlist, 'queue');
            $inputName = Arr::get($playlist, 'input');

            $queue = Queue::coerce($queueName);
            $input = Input::coerce($inputName);

            if (empty($queue) || empty($input)) {
                throw new \InvalidArgumentException(
                    'Queue (' . $queueName . ') or input (' . $inputName . ') is unknown.'
                );
            }

            /** @var Csr $csr */
            $csr = Csr::query()
                ->where('player_id', $player->id)
                ->where('season', $season)
                ->where('queue', $queue->value)
                ->where('input', $input->value)
                ->firstOrNew();

            $csr->player()->associate($player);
            $csr->season = $season;
            $csr->queue = $queue;
            $csr->input = $input;
            $csr->csr = Arr::get($playlist, 'response.current.value');
            $csr->matches_remaining = Arr::get($playlist, 'response.current.measurement_matches_remaining');

            $csr->tier = Arr::get($playlist, 'response.current.tier');
            $csr->tier_start_csr = Arr::get($playlist, 'response.current.tier_start');
            $csr->tier_image_url = Arr::get($playlist, 'response.current.tier_image_url');
            $csr->sub_tier = Arr::get($playlist, 'response.current.sub_tier');

            $csr->next_tier = Arr::get($playlist, 'response.current.next_tier');
            $csr->next_sub_tier = Arr::get($playlist, 'response.current.next_sub_tier');
            $csr->next_csr = Arr::get($playlist, 'response.current.next_tier_start');

            $csr->season_tier = Arr::get($playlist, 'response.season.tier');
            $csr->season_sub_tier = Arr::get($playlist, 'response.season.sub_tier');

            if ($csr->isDirty()) {
                $csr->saveOrFail();
            }
        }

        return $csr ?? null;
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}

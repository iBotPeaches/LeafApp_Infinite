<?php

namespace App\Models;

use App\Enums\Input;
use App\Enums\Queue;
use App\Models\Contracts\HasHaloDotApi;
use Carbon\Carbon;
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
 */
class Csr extends Model implements HasHaloDotApi
{
    use HasFactory;

    public $guarded = [
        'id'
    ];

    public $casts = [
        'input' => Input::class,
        'queue' => Queue::class
    ];

    public function setCsrAttribute(int $csr): void
    {
        $this->attributes['csr'] = $csr === -1 ? null : $csr;
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

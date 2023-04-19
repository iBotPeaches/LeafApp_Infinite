<?php

namespace App\Models;

use App\Models\Contracts\HasHaloDotApi;
use Carbon\Carbon;
use Database\Factories\PlayerBanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $key
 * @property int $player_id
 * @property string $message
 * @property Carbon $ends_at
 * @property string $type
 * @property string $scope
 * @property-read Player $player
 *
 * @method static PlayerBanFactory factory(...$parameters)
 */
class PlayerBan extends Model implements HasHaloDotApi
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public $casts = [
        'ends_at' => 'datetime',
    ];

    public $timestamps = false;

    public static function fromHaloDotApi(array $payload): ?self
    {
        /** @var Player $player */
        $player = Arr::get($payload, '_leaf.player');

        // There is nothing unique about a ban message. So lets make a key of a slugged message with datetime of expiration.
        $message = Arr::get($payload, 'message');
        $endDate = Arr::get($payload, 'end_date');
        $key = md5(Str::slug($message).$endDate);

        /** @var PlayerBan $playerBan */
        $playerBan = self::query()
            ->where('key', $key)
            ->where('player_id', $player->id)
            ->firstOrNew([
                'key' => $key,
            ]);

        $playerBan->player()->associate($player);
        $playerBan->message = $message;
        $playerBan->ends_at = $endDate;
        $playerBan->type = Arr::get($payload, 'properties.type');
        $playerBan->scope = Arr::get($payload, 'properties.scope');

        if ($playerBan->isDirty()) {
            $playerBan->saveOrFail();
        }

        return $playerBan;
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}

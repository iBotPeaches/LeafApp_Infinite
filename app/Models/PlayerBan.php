<?php

namespace App\Models;

use App\Models\Contracts\HasHaloDotApi;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $player_id
 * @property string $message
 * @property Carbon $ends_at
 * @property string $type
 * @property string $scope
 * @property-read Player $player
 */
class PlayerBan extends Model implements HasHaloDotApi
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public $casts = [
        'ends_at' => 'datetime'
    ];

    public static function fromHaloDotApi(array $payload): ?self
    {
        // There is nothing unique about a ban message. So lets make a key of a slugged message with datetime of expiration.
        $key = '';
        // TODO: Implement fromHaloDotApi() method.

        return null;
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}

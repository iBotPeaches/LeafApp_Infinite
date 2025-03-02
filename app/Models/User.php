<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int $id
 * @property int|null $player_id
 * @property string $google_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Player|null $player
 *
 * @method static UserFactory factory(...$parameters)
 */
class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'google_id',
    ];

    /**
     * @return BelongsTo<Player, $this>
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}

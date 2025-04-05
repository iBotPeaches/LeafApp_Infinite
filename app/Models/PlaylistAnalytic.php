<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasPlaylist;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $playlist_id
 * @property int $game_id
 * @property int $player_id
 * @property string $key
 * @property int $place
 * @property float $value
 * @property string $label
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Game $game
 * @property-read Player $player
 * @property-read Playlist $playlist
 */
class PlaylistAnalytic extends Model
{
    use HasFactory, HasPlaylist;

    public $guarded = [
        'id',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function playlist(): BelongsTo
    {
        return $this->belongsTo(Playlist::class);
    }
}

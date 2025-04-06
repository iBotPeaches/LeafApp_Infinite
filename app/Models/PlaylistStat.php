<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $playlist_id
 * @property int $total_matches
 * @property int $total_players
 * @property int $total_unique_players
 * @property-read Playlist $playlist
 */
class PlaylistStat extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public $timestamps = false;

    public function playlist(): BelongsTo
    {
        return $this->belongsTo(Playlist::class);
    }
}

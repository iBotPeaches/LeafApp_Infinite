<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $playlist_id
 * @property string|null $rotation_hash
 * @property array $rotations
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property-read Playlist $playlist
 */
class PlaylistChange extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public function casts(): array
    {
        return [
            'rotations' => 'array',
        ];
    }

    public function playlist(): BelongsTo
    {
        return $this->belongsTo(Playlist::class);
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AnalyticKey;
use App\Support\Analytics\AnalyticInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $playlist_id
 * @property int $game_id
 * @property int|null $player_id
 * @property string $key
 * @property int $place
 * @property float $value
 * @property string $label
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read AnalyticInterface $stat
 * @property-read Game $game
 * @property-read Player|null $player
 * @property-read Playlist $playlist
 */
class PlaylistAnalytic extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public function getStatAttribute(): AnalyticInterface
    {
        return Analytic::getStatFromEnum(AnalyticKey::tryFrom($this->key));
    }

    public function label(): string
    {
        return Str::replace('Ranked', '', $this->stat->title());
    }

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

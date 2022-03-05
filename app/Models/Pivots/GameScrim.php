<?php
declare(strict_types = 1);

namespace App\Models\Pivots;

use App\Models\Game;
use App\Models\Scrim;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property int $scrim_id
 * @property int $game_id
 * @property-read Scrim $scrim
 * @property-read Game $game
 */
class GameScrim extends Pivot
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function scrim(): BelongsTo
    {
        return $this->belongsTo(Scrim::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}

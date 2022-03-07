<?php
declare(strict_types = 1);

namespace App\Models\Pivots;

use App\Models\Game;
use App\Models\Scrim;
use Database\Factories\Pivots\GameScrimFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property int $scrim_id
 * @property int $game_id
 * @property-read Scrim $scrim
 * @property-read Game $game
 * @method static GameScrimFactory factory(...$parameters)
 */
class GameScrim extends Pivot
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    /** @codeCoverageIgnore */
    public function scrim(): BelongsTo
    {
        return $this->belongsTo(Scrim::class);
    }

    /** @codeCoverageIgnore */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}

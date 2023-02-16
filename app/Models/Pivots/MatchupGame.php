<?php

declare(strict_types=1);

namespace App\Models\Pivots;

use App\Models\Game;
use App\Models\Matchup;
use Database\Factories\Pivots\MatchupGameFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property int $matchup_id
 * @property int $game_id
 * @property Matchup $matchup
 * @property Matchup $game
 *
 * @method static MatchupGameFactory factory(...$parameters)
 */
class MatchupGame extends Pivot
{
    use HasFactory;

    public $guarded = [
        'id',
        'matchup_id',
        'game_id',
    ];

    public $timestamps = false;

    public function matchup(): BelongsTo
    {
        return $this->belongsTo(Matchup::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}

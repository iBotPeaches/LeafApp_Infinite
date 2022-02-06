<?php
declare(strict_types=1);

namespace App\Models\Pivots;

use App\Enums\Outcome;
use App\Models\Traits\HasAccuracy;
use App\Models\Traits\HasCsr;
use App\Models\Traits\HasKd;
use App\Models\Traits\HasScoring;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $player_id
 * @property int $game_id
 * @property Outcome $outcome
 * @property int $kills
 * @property int $deaths
 * @property int $assists
 * @property float $kd
 * @property float $kda
 * @property float $accuracy
 * @property int $score
 * @property int $rank
 * @property int $pre_csr
 * @property int $post_csr
 */
class PersonalResult extends Pivot
{
    use HasKd, HasScoring, HasCsr, HasAccuracy;

    public $casts = [
        'outcome' => Outcome::class
    ];
}

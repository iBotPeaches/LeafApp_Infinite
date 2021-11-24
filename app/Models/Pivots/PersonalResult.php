<?php
declare(strict_types=1);

namespace App\Models\Pivots;

use App\Enums\Outcome;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $player_id
 * @property int $game_id
 * @property Outcome $outcome
 * @property int $kills
 * @property int $deaths
 * @property float $kd
 * @property float $kda
 * @property float $accuracy
 * @property int $score
 * @property int $rank
 */
class PersonalResult extends Pivot
{
    public $casts = [
        'outcome' => Outcome::class
    ];

    public function getKdAttribute(float $value): string
    {
        return number_format($value, 2);
    }

    public function getKdaAttribute(float $value): string
    {
        return number_format($value, 2);
    }

    public function getScoreAttribute(int $value): string
    {
        return number_format($value);
    }

    public function getVictoryColor(): string
    {
        if ($this->outcome->is(Outcome::WIN())) {
            return 'has-background-success-light';
        }

        if ($this->outcome->is(Outcome::LOSS())) {
            return 'has-background-warning-light';
        }

        if ($this->outcome->is(Outcome::LEFT())) {
            return 'has-background-danger-light';
        }

        return '';
    }

    public function getKdColor(): string
    {
        return $this->kd >= 1
            ? 'has-background-success-light'
            : 'has-background-danger-light';
    }

    public function getKdaColor(): string
    {
        return $this->kda >= 1
            ? 'has-background-success-light'
            : 'has-background-danger-light';
    }
}

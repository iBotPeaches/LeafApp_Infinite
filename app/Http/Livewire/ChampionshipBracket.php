<?php
declare(strict_types=1);

namespace App\Http\Livewire;

use App\Enums\Bracket;
use App\Models\Championship;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

class ChampionshipBracket extends Component
{
    public Championship $championship;
    public string $bracket;
    public int $round;

    public function render(): View
    {
        /** @var Bracket $bracketEnum */
        $bracketEnum = Bracket::coerce($this->bracket);

        $matchups = $this->championship->matchups
            ->where('group', $bracketEnum->toNumerical())
            ->where('round', $this->round)
            ->sortByDesc('started_at');

        $rounds = $this->championship->matchups
            ->where('group', $bracketEnum->toNumerical())
            ->groupBy('round')
            ->map(function (\Illuminate\Support\Collection $row) {
                return $row->count();
            })
            ->reverse();

        return view('livewire.championship-bracket', [
            'bracket' => $this->bracket,
            'round' => $this->round,
            'matchups' => $matchups,
            'rounds' => $rounds,
        ]);
    }
}

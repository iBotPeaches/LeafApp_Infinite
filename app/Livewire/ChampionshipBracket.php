<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\Bracket;
use App\Models\Championship;
use App\Support\Bracket\BracketDecorator;
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
            ->sortBy('round')
            ->groupBy('round')
            ->map(function (\Illuminate\Support\Collection $row) {
                return $row->count();
            });

        $roundMatchups = $this->championship->matchups;

        if (! $bracketEnum->is(Bracket::SUMMARY())) {
            $roundMatchups = $roundMatchups->where('round', $this->round);
        }

        $brackets = new BracketDecorator($bracketEnum, $roundMatchups);

        return view('livewire.championship-bracket', [
            'summary' => $brackets->data,
            'bracket' => $this->bracket,
            'round' => $this->round,
            'matchups' => $matchups,
            'rounds' => $rounds,
        ]);
    }
}

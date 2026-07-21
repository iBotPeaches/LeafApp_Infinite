<?php

namespace App\Livewire;

use App\Enums\AnalyticKey;
use App\Models\Analytic;
use App\Models\Game;
use Illuminate\View\View;
use Livewire\Component;

class GameBadges extends Component
{
    public Game $game;

    public bool $readyToLoad = false;

    public function loadBadges(): void
    {
        $this->readyToLoad = true;
    }

    public function render(): View
    {
        $topTen = $this->game->analytics()
            ->with('player')
            ->where('place', '<=', 10)
            ->orderBy('place', 'ASC')
            ->get()
            ->each(function (Analytic $analytic) {
                $analyticEnumKey = AnalyticKey::tryFrom($analytic->key);
                $analytic['enum'] = Analytic::getStatFromEnum($analyticEnumKey);
            });

        return view('livewire.game-badges', [
            'topTen' => $this->readyToLoad ? $topTen : null,
        ]);
    }
}

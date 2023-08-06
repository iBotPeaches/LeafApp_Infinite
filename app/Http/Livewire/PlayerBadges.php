<?php

namespace App\Http\Livewire;

use App\Enums\AnalyticKey;
use App\Models\Analytic;
use App\Models\Player;
use Illuminate\View\View;
use Livewire\Component;

class PlayerBadges extends Component
{
    public Player $player;

    public bool $readyToLoad = false;

    public function loadBadges(): void
    {
        $this->readyToLoad = true;
    }

    public function render(): View
    {
        $topTen = $this->player->analytics()
            ->with('player')
            ->where('place', '<=', 10)
            ->orderBy('place', 'ASC')
            ->get()
            ->each(function (Analytic $analytic) {
                $analyticEnumKey = AnalyticKey::tryFrom($analytic->key);
                $analytic['enum'] = Analytic::getStatFromEnum($analyticEnumKey);
            });

        $medals = $this->player->medals()
            ->with('medal')
            ->whereNull('season_id')
            ->where('place', '<=', 10)
            ->orderBy('place', 'ASC')
            ->orderBy('value', 'DESC')
            ->get();

        return view('livewire.player-badges', [
            'medals' => $this->readyToLoad ? $medals : null,
            'topTen' => $this->readyToLoad ? $topTen : null,
        ]);
    }
}

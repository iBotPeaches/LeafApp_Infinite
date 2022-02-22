<?php
declare(strict_types = 1);

namespace App\Http\Livewire;

use App\Models\Player;
use Illuminate\View\View;
use Livewire\Component;

class CompetitivePage extends Component
{
    public Player $player;

    // @phpstan-ignore-next-line
    public $listeners = [
        '$refresh'
    ];

    public function render(): View
    {
        return view('livewire.competitive-page', [
            'current' => $this->player->currentRanked(),
            'season' => $this->player->seasonHighRanked(),
            'allTime' => $this->player->allTimeRanked(),
        ]);
    }
}

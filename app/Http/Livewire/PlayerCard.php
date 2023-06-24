<?php

namespace App\Http\Livewire;

use App\Models\Player;
use Illuminate\View\View;
use Livewire\Component;

class PlayerCard extends Component
{
    public Player $player;

    // @phpstan-ignore-next-line
    public $listeners = [
        '$refresh',
    ];

    public function render(): View
    {
        return view('livewire.player-card', [
            'player' => $this->player,
        ]);
    }
}

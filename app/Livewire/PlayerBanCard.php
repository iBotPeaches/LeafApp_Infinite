<?php

namespace App\Livewire;

use App\Models\Player;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class PlayerBanCard extends Component
{
    public Player $player;

    public function banCheck(): void
    {
        if (Auth::guest()) {
            $this->redirectRoute('login');

            return;
        }

        $this->redirectRoute('banCheck', $this->player);
    }

    public function render(): View
    {
        return view('livewire.player-ban-card', [
            'player' => $this->player,
        ]);
    }
}

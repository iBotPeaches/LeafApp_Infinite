<?php

namespace App\Livewire;

use App\Models\Player;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;
use Livewire\Component;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PlayerBanCard extends Component
{
    public Player $player;

    // @phpstan-ignore-next-line
    public $listeners = [
        '$refresh',
    ];

    public function banCheck(): RedirectResponse
    {
        if (Auth::guest()) {
            return redirect()->route('login');
        }

        $this->player->checkForBanFromDotApi();

        return redirect()->route('player', $this->player);
    }

    public function render(): View
    {
        return view('livewire.player-ban-card', [
            'player' => $this->player,
        ]);
    }
}

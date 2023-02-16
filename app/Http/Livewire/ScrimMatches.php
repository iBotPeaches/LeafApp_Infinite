<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Scrim;
use Illuminate\View\View;
use Livewire\Component;

class ScrimMatches extends Component
{
    public Scrim $scrim;

    public function render(): View
    {
        $this->scrim->load([
            'games.players',
            'games.teams.players',
            'games.category',
            'games.map',
        ]);

        return view('livewire.scrim-matches', [
            'games' => $this->scrim->games,
        ]);
    }
}

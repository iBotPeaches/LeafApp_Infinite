<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Scrim;
use App\Support\Scrim\ScrimDecorator;
use Illuminate\View\View;
use Livewire\Component;

class ScrimPlayers extends Component
{
    public Scrim $scrim;

    public function render(): View
    {
        $scrimDecorator = new ScrimDecorator($this->scrim);

        return view('livewire.scrim-players', [
            'gamePlayers' => $scrimDecorator->mergedStats,
        ]);
    }
}

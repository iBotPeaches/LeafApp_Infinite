<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Player;
use App\Support\Modes\ModeDecorator;
use App\Support\Session\SeasonSession;
use Illuminate\View\View;
use Livewire\Component;

class ModePage extends Component
{
    public Player $player;

    // @phpstan-ignore-next-line
    public $listeners = [
        '$refresh',
    ];

    public function render(): View
    {
        $season = SeasonSession::get();

        $modes = new ModeDecorator($this->player);

        return view('livewire.mode-page', [
            'best' => $modes->bestModes(),
            'worse' => $modes->worseModes(),
        ]);
    }
}

<?php
declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Player;
use Livewire\Component;

class OverviewPage extends Component
{
    public Player $player;

    // @phpstan-ignore-next-line
    public $listeners = [
        '$refresh'
    ];

    public function render()
    {
        return view('livewire.overview-page', [
            'serviceRecord' => $this->player->serviceRecord
        ]);
    }
}

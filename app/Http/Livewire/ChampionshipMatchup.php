<?php
declare(strict_types = 1);

namespace App\Http\Livewire;

use App\Models\Championship;
use App\Models\Matchup;
use Illuminate\View\View;
use Livewire\Component;

class ChampionshipMatchup extends Component
{
    public Championship $championship;
    public Matchup $matchup;

    public function render(): View
    {
        return view('livewire.championship-matchup', [
            'championship' => $this->championship,
            'matchup' => $this->matchup
        ]);
    }
}

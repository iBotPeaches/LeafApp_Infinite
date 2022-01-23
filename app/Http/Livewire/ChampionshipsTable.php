<?php
declare(strict_types = 1);

namespace App\Http\Livewire;

use App\Models\Championship;
use Illuminate\View\View;
use Livewire\Component;

class ChampionshipsTable extends Component
{
    public function render(): View
    {
        $championships = Championship::query()
            ->orderBy('started_at')
            ->paginate();

        return view('livewire.championships-table', [
            'championships' => $championships
        ]);
    }
}

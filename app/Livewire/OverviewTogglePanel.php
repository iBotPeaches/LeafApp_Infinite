<?php

namespace App\Livewire;

use App\Models\Overview;
use Illuminate\View\View;
use Livewire\Component;

class OverviewTogglePanel extends Component
{
    public Overview $overview;

    public int $mapId = -1;

    public int $gametypeId = -1;

    public function onMapChange(): void
    {
        //
    }

    public function onGametypeChange(): void
    {
        //
    }

    public function render(): View
    {
        $maps = $this->overview->maps
            ->sortBy('released_at');

        $gametypes = $this->overview->gametypes
            ->sortBy('name');

        return view('livewire.overview-toggle-panel', [
            'maps' => $maps,
            'gametypes' => $gametypes,
        ]);
    }
}

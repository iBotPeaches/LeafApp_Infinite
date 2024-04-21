<?php

namespace App\Livewire;

use App\Models\Overview;
use App\Support\Session\OverviewGametypeSession;
use App\Support\Session\OverviewMapSession;
use Illuminate\View\View;
use Livewire\Component;

class OverviewTogglePanel extends Component
{
    public Overview $overview;

    public int $mapId = -1;

    public int $gametypeId = -1;

    public function onMapChange(): void
    {
        OverviewMapSession::set($this->overview, $this->mapId);
        $this->emitToComponents();
    }

    public function onGametypeChange(): void
    {
        OverviewGametypeSession::set($this->overview, $this->gametypeId);
        $this->emitToComponents();
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

    private function emitToComponents(): void
    {
        $this->dispatch('$refresh')->to(OverviewOverview::class);
        $this->dispatch('$refresh')->to(OverviewMatchesTable::class);
    }
}

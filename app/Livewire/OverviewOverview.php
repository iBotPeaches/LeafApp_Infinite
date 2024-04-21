<?php

namespace App\Livewire;

use App\Models\Overview;
use App\Support\Session\OverviewGametypeSession;
use App\Support\Session\OverviewMapSession;
use Illuminate\View\View;
use Livewire\Component;

class OverviewOverview extends Component
{
    public Overview $overview;

    // @phpstan-ignore-next-line
    public $listeners = [
        '$refresh',
    ];

    public function render(): View
    {
        $mapId = OverviewMapSession::get($this->overview);
        $gametypeId = OverviewGametypeSession::get($this->overview);

        $stats = $this->overview->stats
            ->when($mapId === -1, fn ($query) => $query->whereNull('overview_map_id'))
            ->when($mapId !== -1, fn ($query) => $query->where('overview_map_id', $mapId))
            ->when($gametypeId === -1, fn ($query) => $query->whereNull('overview_gametype_id'))
            ->when($gametypeId !== -1, fn ($query) => $query->where('overview_gametype_id', $gametypeId));

        return view('livewire.overview-overview', [
            'overview' => $this->overview,
            'overviewStat' => $stats->first(),
        ]);
    }
}

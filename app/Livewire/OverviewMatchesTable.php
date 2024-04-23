<?php

namespace App\Livewire;

use App\Livewire\Traits\HasScrimEditor;
use App\Models\Game;
use App\Models\Overview;
use App\Models\OverviewGametype;
use App\Models\OverviewMap;
use App\Support\Session\OverviewGametypeSession;
use App\Support\Session\OverviewMapSession;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class OverviewMatchesTable extends Component
{
    use HasScrimEditor;
    use WithPagination;

    public Overview $overview;

    // @phpstan-ignore-next-line
    public $listeners = [
        '$refresh',
    ];

    public function render(): View
    {
        $overviewMapId = OverviewMapSession::get($this->overview);
        $overviewGametypeId = OverviewGametypeSession::get($this->overview);

        // The default filter needs to be all maps of this overview
        $mapIds = $this->overview->maps->pluck('map_id')->toArray();

        /** @var OverviewMap|null $filteredMapId */
        $filteredMapId = $this->overview->maps->firstWhere('id', $overviewMapId);

        /** @var OverviewGametype|null $filteredGametypeIds */
        $filteredGametypeIds = $this->overview->gametypes->firstWhere('id', $overviewGametypeId);

        $games = Game::query()
            ->with(['playlist', 'map', 'category', 'teams'])
            ->when($overviewMapId === -1, fn ($query) => $query->whereIn('map_id', $mapIds))
            ->when($overviewMapId !== -1, fn ($query) => $query->where('map_id', $filteredMapId?->map_id))
            ->when($overviewGametypeId !== -1, fn ($query) => $query->whereIn('gamevariant_id', $filteredGametypeIds?->gamevariant_ids))
            ->orderByDesc('occurred_at')
            ->simplePaginate(16);

        return view('livewire.overview-matches-table', [
            'overview' => $this->overview,
            'games' => $games,
        ]);
    }
}

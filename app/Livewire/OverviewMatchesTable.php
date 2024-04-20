<?php

namespace App\Livewire;

use App\Livewire\Traits\HasScrimEditor;
use App\Models\Game;
use App\Models\Overview;
use App\Support\Session\OverviewMapSession;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class OverviewMatchesTable extends Component
{
    use HasScrimEditor;
    use WithPagination;

    public Overview $overview;

    public function paginationView(): string
    {
        return 'pagination::bulma';
    }

    public function render(): View
    {
        $mapId = OverviewMapSession::get($this->overview);
        $mapIds = $this->overview->maps->pluck('map_id')->toArray();

        $games = Game::query()
            ->with(['playlist', 'map', 'category'])
            ->when($mapId === -1, fn ($query) => $query->whereIn('map_id', $mapIds))
            ->when($mapId !== -1, fn ($query) => $query->where('map_id', $mapId))
            ->orderByDesc('occurred_at')
            ->simplePaginate(16);

        return view('livewire.overview-matches-table', [
            'overview' => $this->overview,
            'games' => $games,
        ]);
    }
}

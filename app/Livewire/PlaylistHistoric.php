<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Playlist;
use App\Models\PlaylistChange;
use App\Support\Rotations\RotationDecorator;
use Illuminate\View\View;
use Livewire\Component;

class PlaylistHistoric extends Component
{
    public Playlist $playlist;

    public function render(): View
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, PlaylistChange> $changes */
        $changes = $this->playlist->changes()->orderByDesc('created_at')->get();

        $historicRotations = $changes->map(function (PlaylistChange $change) {
            $decorator = new RotationDecorator((array) $change->rotations);

            return [
                'date' => $change->created_at,
                'rotations' => $decorator->rotations->sortBy('mapName'),
                'maps' => $decorator->mapNames->sortDesc(),
                'gametypes' => $decorator->gametypeNames->sortDesc(),
            ];
        });

        return view('livewire.playlist-historic', [
            'historicRotations' => $historicRotations,
        ]);
    }
}

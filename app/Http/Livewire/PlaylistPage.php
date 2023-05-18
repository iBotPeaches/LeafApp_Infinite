<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Playlist;
use App\Support\Rotations\RotationDecorator;
use Illuminate\View\View;
use Livewire\Component;

class PlaylistPage extends Component
{
    public Playlist $playlist;

    public function render(): View
    {
        $decorator = new RotationDecorator((array) $this->playlist->rotations);

        return view('livewire.playlist-page', [
            'playlist' => $this->playlist,
            'rotations' => $decorator->rotations->sortBy('mapName'),
            'maps' => $decorator->mapNames->sortDesc(),
            'gametypes' => $decorator->gametypeNames->sortDesc(),
        ]);
    }
}

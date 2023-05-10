<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Playlist;
use Illuminate\View\View;
use Livewire\Component;

class PlaylistPage extends Component
{
    public Playlist $playlist;

    public function render(): View
    {
        return view('livewire.playlist-page', [
            'playlist' => $this->playlist
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Playlist;
use Illuminate\View\View;
use Livewire\Component;

class PlaylistStat extends Component
{
    public Playlist $playlist;

    public function render(): View
    {
        $stat = $this->playlist->stat;

        return view('livewire.playlist-stats', [
            'stat' => $stat,
        ]);
    }
}

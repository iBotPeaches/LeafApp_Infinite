<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Playlist;
use Illuminate\View\View;
use Livewire\Component;

class PlaylistStats extends Component
{
    public Playlist $playlist;

    public function render(): View
    {
        $stat = $this->playlist->stat;
        $analytics = $this->playlist
            ->analytics
            ->load(['player', 'game'])
            ->sortBy('place')
            ->groupBy('key');

        return view('livewire.playlist-stats', [
            'stat' => $stat,
            'analytics' => $analytics,
        ]);
    }
}

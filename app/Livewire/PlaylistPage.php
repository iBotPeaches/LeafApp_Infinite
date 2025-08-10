<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Actions\Playlist\CompareRotations;
use App\Models\Playlist;
use App\Models\PlaylistChange;
use App\Support\Rotations\RotationDecorator;
use App\Support\Schedule\ScheduleTimer;
use App\Support\Schedule\ScheduleTimerInterface;
use Illuminate\View\View;
use Livewire\Component;

class PlaylistPage extends Component
{
    public Playlist $playlist;

    public function render(): View
    {
        $decorator = new RotationDecorator((array) $this->playlist->rotations);

        /** @var ScheduleTimer $timer */
        $timer = resolve(ScheduleTimerInterface::class);

        // Get the most recent previous rotation
        $previousChange = PlaylistChange::query()
            ->where('playlist_id', $this->playlist->id)
            ->where('rotation_hash', '!=', \App\Actions\Playlist\HashRotations::execute($this->playlist->rotations))
            ->latest('created_at')
            ->first();

        $rotationChanges = null;
        $previousDate = null;
        $currentDate = null;

        if ($previousChange) {
            $rotationChanges = CompareRotations::execute(
                $this->playlist->rotations ?? [],
                $previousChange->rotations ?? []
            );
            $previousDate = $previousChange->created_at;
            
            // Get the current rotation date
            $currentChange = PlaylistChange::query()
                ->where('playlist_id', $this->playlist->id)
                ->where('rotation_hash', \App\Actions\Playlist\HashRotations::execute($this->playlist->rotations))
                ->latest('created_at')
                ->first();
            
            $currentDate = $currentChange?->created_at;
        }

        return view('livewire.playlist-page', [
            'playlist' => $this->playlist,
            'rotations' => $decorator->rotations->sortBy('mapName'),
            'maps' => $decorator->mapNames->sortDesc(),
            'gametypes' => $decorator->gametypeNames->sortDesc(),
            'nextDate' => $timer->metadataRefreshDate,
            'rotationChanges' => $rotationChanges,
            'previousDate' => $previousDate,
            'currentDate' => $currentDate,
        ]);
    }
}

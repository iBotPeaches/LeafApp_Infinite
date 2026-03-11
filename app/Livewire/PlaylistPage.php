<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Playlist;
use App\Models\PlaylistChange;
use App\Support\Rotations\RotationDecorator;
use App\Support\Schedule\ScheduleTimer;
use App\Support\Schedule\ScheduleTimerInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
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

        /** @var EloquentCollection<int, PlaylistChange> $changes */
        $changes = $this->playlist->changes()->orderByDesc('created_at')->limit(2)->get();
        /** @var PlaylistChange|null $currentChange */
        $currentChange = $changes->first();
        /** @var PlaylistChange|null $previousChange */
        $previousChange = $changes->count() > 1 ? $changes->last() : null;

        $mapDiffs = null;
        $gametypeDiffs = null;

        if ($previousChange) {
            $previousDecorator = new RotationDecorator((array) $previousChange->rotations);
            $mapDiffs = $this->computeDiffs($decorator->mapNames, $previousDecorator->mapNames);
            $gametypeDiffs = $this->computeDiffs($decorator->gametypeNames, $previousDecorator->gametypeNames);
        }

        return view('livewire.playlist-page', [
            'playlist' => $this->playlist,
            'rotations' => $decorator->rotations->sortBy('mapName'),
            'maps' => $decorator->mapNames->sortDesc(),
            'gametypes' => $decorator->gametypeNames->sortDesc(),
            'nextDate' => $timer->metadataRefreshDate,
            'mapDiffs' => $mapDiffs,
            'gametypeDiffs' => $gametypeDiffs,
            'currentChange' => $currentChange,
            'previousChange' => $previousChange,
        ]);
    }

    private function computeDiffs(Collection $current, Collection $previous): array
    {
        $diffs = [];
        $allKeys = $current->keys()->merge($previous->keys())->unique();

        foreach ($allKeys as $key) {
            $currentValue = $current->get($key);
            $previousValue = $previous->get($key);

            if ($currentValue !== null && $previousValue === null) {
                $diffs[$key] = ['type' => 'added'];
            } elseif ($currentValue === null && $previousValue !== null) {
                $diffs[$key] = ['type' => 'removed'];
            } else {
                $diff = $currentValue - $previousValue;
                if (abs($diff) >= 0.01) {
                    $diffs[$key] = ['type' => 'changed', 'diff' => $diff];
                }
            }
        }

        return $diffs;
    }
}

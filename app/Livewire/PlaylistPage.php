<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Playlist;
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

        // Initialize comparison data
        $rotationComparisons = collect();
        $mapComparisons = collect();
        $gametypeComparisons = collect();
        $hasLastChange = false;

        // Check if we have a last change for the playlist
        $lastChange = $this->playlist->changes->sortByDesc('created_at')->skip(1)->first();
        if ($lastChange !== null) {
            $hasLastChange = true;
            $lastDecorator = new RotationDecorator((array) $lastChange->rotations);

            // Compare rotations
            $rotationComparisons = $this->compareRotations($decorator->rotations, $lastDecorator->rotations);

            // Compare maps
            $mapComparisons = $this->compareMapsOrGametypes($decorator->mapNames, $lastDecorator->mapNames);

            // Compare gametypes
            $gametypeComparisons = $this->compareMapsOrGametypes($decorator->gametypeNames, $lastDecorator->gametypeNames);
        }

        /** @var ScheduleTimer $timer */
        $timer = resolve(ScheduleTimerInterface::class);

        return view('livewire.playlist-page', [
            'playlist' => $this->playlist,
            'rotations' => $decorator->rotations->sortBy('mapName'),
            'maps' => $decorator->mapNames->sortDesc(),
            'gametypes' => $decorator->gametypeNames->sortDesc(),
            'nextDate' => $timer->metadataRefreshDate,
            'hasLastChange' => $hasLastChange,
            'rotationComparisons' => $rotationComparisons,
            'mapComparisons' => $mapComparisons,
            'gametypeComparisons' => $gametypeComparisons,
        ]);
    }

    /**
     * Compare two collections of rotations and return the differences.
     *
     * @param \Illuminate\Support\Collection $currentRotations
     * @param \Illuminate\Support\Collection $previousRotations
     * @return \Illuminate\Support\Collection
     */
    private function compareRotations($currentRotations, $previousRotations)
    {
        $comparisons = collect();

        // Create lookup arrays for faster comparison
        $previousRotationsByName = $previousRotations->keyBy('combinedName');

        // Check each current rotation
        foreach ($currentRotations as $rotation) {
            $status = 'unchanged';
            $weightChange = null;

            // Check if this rotation existed in the previous version
            if ($previousRotationsByName->has($rotation->combinedName)) {
                $previousRotation = $previousRotationsByName->get($rotation->combinedName);

                // Check if weight changed
                if (abs($rotation->weightPercent - $previousRotation->weightPercent) > 0.01) {
                    $status = 'changed';
                    $weightChange = $rotation->weightPercent - $previousRotation->weightPercent;
                }
            } else {
                $status = 'new';
            }

            $comparisons->put($rotation->combinedName, [
                'status' => $status,
                'weightChange' => $weightChange,
            ]);
        }

        // Check for removed rotations
        foreach ($previousRotations as $rotation) {
            if (!$currentRotations->contains('combinedName', $rotation->combinedName)) {
                $comparisons->put($rotation->combinedName, [
                    'status' => 'removed',
                    'weightChange' => null,
                ]);
            }
        }

        return $comparisons;
    }

    /**
     * Compare two collections of maps or gametypes and return the differences.
     *
     * @param \Illuminate\Support\Collection $current
     * @param \Illuminate\Support\Collection $previous
     * @return \Illuminate\Support\Collection
     */
    private function compareMapsOrGametypes($current, $previous)
    {
        $comparisons = collect();

        // Check each current item
        foreach ($current as $name => $weight) {
            $status = 'unchanged';
            $weightChange = null;

            // Check if this item existed in the previous version
            if ($previous->has($name)) {
                $previousWeight = $previous->get($name);

                // Check if weight changed
                if (abs($weight - $previousWeight) > 0.01) {
                    $status = 'changed';
                    $weightChange = $weight - $previousWeight;
                }
            } else {
                $status = 'new';
            }

            $comparisons->put($name, [
                'status' => $status,
                'weightChange' => $weightChange,
            ]);
        }

        // Check for removed items
        foreach ($previous as $name => $weight) {
            if (!$current->has($name)) {
                $comparisons->put($name, [
                    'status' => 'removed',
                    'weightChange' => null,
                ]);
            }
        }

        return $comparisons;
    }
}

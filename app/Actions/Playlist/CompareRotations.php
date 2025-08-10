<?php

declare(strict_types=1);

namespace App\Actions\Playlist;

use App\Support\Rotations\RotationDecorator;
use Illuminate\Support\Collection;

class CompareRotations
{
    public static function execute(array $currentRotations, array $previousRotations): array
    {
        $currentDecorator = new RotationDecorator($currentRotations);
        $previousDecorator = new RotationDecorator($previousRotations);

        return [
            'maps' => self::compareItems($currentDecorator->mapNames, $previousDecorator->mapNames),
            'gametypes' => self::compareItems($currentDecorator->gametypeNames, $previousDecorator->gametypeNames),
        ];
    }

    private static function compareItems(Collection $current, Collection $previous): array
    {
        $changes = [];

        // Check for changes in existing items
        foreach ($current as $name => $currentPercent) {
            if ($previous->has($name)) {
                $previousPercent = $previous->get($name);
                $diff = $currentPercent - $previousPercent;
                
                if (abs($diff) >= 0.01) { // Only show changes >= 0.01%
                    $changes[$name] = [
                        'type' => 'changed',
                        'current' => $currentPercent,
                        'previous' => $previousPercent,
                        'difference' => $diff,
                    ];
                } else {
                    $changes[$name] = [
                        'type' => 'unchanged',
                        'current' => $currentPercent,
                    ];
                }
            } else {
                // New item added
                $changes[$name] = [
                    'type' => 'added',
                    'current' => $currentPercent,
                ];
            }
        }

        // Check for removed items
        foreach ($previous as $name => $previousPercent) {
            if (!$current->has($name)) {
                $changes[$name] = [
                    'type' => 'removed',
                    'previous' => $previousPercent,
                ];
            }
        }

        return $changes;
    }
}
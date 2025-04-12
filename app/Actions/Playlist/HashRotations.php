<?php

declare(strict_types=1);

namespace App\Actions\Playlist;

class HashRotations
{
    public static function execute(array $rotations = []): string
    {
        $items = collect($rotations)
            ->sortBy('name')
            ->values()
            ->all();

        // @phpstan-ignore-next-line argument.type
        return md5(json_encode($items));
    }
}

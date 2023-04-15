<?php

declare(strict_types=1);

namespace Tests\Traits;

trait HasProxiedImageUrls
{
    public function getAssetUrl(string $imageName): string
    {
        $imageObject = [
            'identifier' => 'hi',
            'path' => $imageName,
            'options' => [
                'branch' => 'Waypoint',
            ],
        ];

        $imageJson = json_encode($imageObject);

        return 'https://api.halodotapi.com/games/halo-infinite/tooling/cms-images?hash='.base64_encode($imageJson);
    }
}

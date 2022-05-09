<?php
declare(strict_types=1);

namespace Tests\Mocks\Appearance;

use Tests\Mocks\BaseMock;
use Tests\Mocks\Traits\HasErrorFunctions;

class MockAppearanceService extends BaseMock
{
    use HasErrorFunctions;

    public function success(?string $gamertag = null, ?string $imageName = null, ?string $backdropName = null): array
    {
        $imageName ??= 'images/file/progression/Inventory/Emblems/olympus_nicekitty_emblem.png';
        $backdropName ??= 'images/file/progression/backgrounds/ui_background_reach-helmet-1.png';

        return [
            'data' => [
                'emblem_url' => $this->getAssetUrl($imageName),
                'backdrop_image_url' => $this->getAssetUrl($backdropName),
                'service_tag' => $this->faker->lexify('????'),
            ],
            'additional' => [
                'parameters' => [
                    'gamertag' => $gamertag ?? $this->faker->word
                ]
            ]
        ];
    }

    public function invalidSuccess(?string $gamertag = null): array
    {
        return [
            'data' => [
                'emblem_url' => $this->faker->imageUrl,
                'backdrop_image_url' => $this->faker->imageUrl,
                'service_tag' => $this->faker->lexify('????'),
            ],
            'additional' => [
                'parameters' => [
                    'gamertag' => $gamertag ?? $this->faker->word
                ]
            ]
        ];
    }

    private function getAssetUrl(string $imageName): string
    {
        return 'https://assets.halo.autocode.gg/externals/infinite/cms-images/?hash=' . base64_encode($imageName);
    }
}

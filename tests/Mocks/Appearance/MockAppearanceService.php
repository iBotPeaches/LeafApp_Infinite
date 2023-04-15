<?php

declare(strict_types=1);

namespace Tests\Mocks\Appearance;

use Tests\Mocks\BaseMock;
use Tests\Mocks\Traits\HasErrorFunctions;
use Tests\Traits\HasProxiedImageUrls;

class MockAppearanceService extends BaseMock
{
    use HasErrorFunctions, HasProxiedImageUrls;

    public function success(?string $gamertag = null, ?string $emblemName = null, ?string $backdropName = null): array
    {
        $emblemName ??= 'images/file/progression/Inventory/Emblems/olympus_nicekitty_emblem.png';
        $nameplateName = 'images/nameplates/104-001-olympus-nicek-3112937b_n1405407205.png';
        $backdropName ??= 'images/file/progression/backgrounds/ui_background_reach-helmet-1.png';
        $actionPoseName = 'progression/Inventory/Spartan/ActionPoses/101-000-menu-stance-r-4c08c2fe-SM.png';

        return [
            'data' => [
                'service_tag' => $this->faker->lexify('????'),
                'image_urls' => [
                    'emblem' => $this->getAssetUrl($emblemName),
                    'nameplate' => $this->getAssetUrl($nameplateName),
                    'backdrop' => $this->getAssetUrl($backdropName),
                    'action_pose' => $this->getAssetUrl($actionPoseName),
                ],
            ],
            'additional' => [
                'params' => [
                    'gamertag' => $gamertag ?? $this->faker->word,
                ],
            ],
        ];
    }

    public function invalidSuccess(?string $gamertag = null): array
    {
        return [
            'data' => [
                'service_tag' => $this->faker->lexify('????'),
                'image_urls' => [
                    'emblem' => $this->faker->imageUrl,
                    'nameplate' => $this->faker->imageUrl,
                    'backdrop' => $this->faker->imageUrl,
                    'action_pose' => $this->faker->imageUrl,
                ],
            ],
            'additional' => [
                'params' => [
                    'gamertag' => $gamertag ?? $this->faker->word,
                ],
            ],
        ];
    }
}

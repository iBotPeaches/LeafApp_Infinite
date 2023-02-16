<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Player> */
class PlayerFactory extends Factory
{
    protected $model = Player::class;

    public function definition(): array
    {
        return [
            'xuid' => $this->faker->numerify('################'),
            'gamertag' => $this->faker->word.$this->faker->unixTime,
            'service_tag' => $this->faker->lexify,
            'is_private' => false,
            'emblem_url' => $this->assetUrl('test-image.png'),
            'backdrop_url' => $this->assetUrl('test-image2.png'),
        ];
    }

    private function assetUrl(string $imageName): string
    {
        return 'https://assets.halo.autocode.gg/externals/infinite/cms-images/?hash='.base64_encode($imageName);
    }
}

<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\Traits\HasProxiedImageUrls;

/** @extends Factory<Player> */
class PlayerFactory extends Factory
{
    use HasProxiedImageUrls;

    protected $model = Player::class;

    public function definition(): array
    {
        return [
            'xuid' => $this->faker->numerify('################'),
            'gamertag' => $this->faker->word.$this->faker->unixTime,
            'service_tag' => $this->faker->lexify,
            'is_private' => false,
            'emblem_url' => $this->getAssetUrl('test-image.png'),
            'backdrop_url' => $this->getAssetUrl('test-image2.png'),
        ];
    }
}

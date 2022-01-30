<?php
declare(strict_types=1);

namespace Tests\Mocks\Championship;

use App\Services\FaceIt\Enums\Region;
use Tests\Mocks\BaseMock;

class MockMatchupService extends BaseMock
{
    public function success(): array
    {
        return [
            'match_id' => '1-' . $this->faker->uuid,
            'version' => 2,
            'game' => 'halo_infinite',
            'region' => Region::getRandomValue(),
            'competition_id' => $this->faker->uuid,
            'competition_type' => 'championship',
            'competition_name' => $this->faker->word,
            'organizer_id' => $this->faker->uuid,
            'teams' => [
                'faction1' => $this->teamBlock(),
                'faction2' => $this->teamBlock()
            ],
            'calculate_elo' => true,
            'started_at' => now()->timestamp,
            'finished_at' => now()->timestamp,
            'chat_room_id' => 'match-1-' . $this->faker->uuid,
            'best_of' => $this->faker->randomElement([3, 5, 7]),
            'results' => [
                'winner' => 'faction1',
                'score' => [
                    'faction1' => 3,
                    'faction2' => 2
                ]
            ],
            'status' => 'FINISHED',
            'round' => $this->faker->numberBetween(1, 12),
            'group' => $this->faker->numberBetween(1, 2),
            'faceit_url' => $this->faker->url
        ];
    }

    private function playerBlock(): array
    {
        return [
            'player_id' => $this->faker->uuid,
            'nickname' => $this->faker->word,
            'avatar' => '',
            'membership' => 'free',
            'game_player_id' => $this->faker->word,
            'game_player_name' => $this->faker->word,
            'game_skill_level' => $this->faker->numberBetween(0, 9),
            'anticheat_required' => false
        ];
    }

    private function teamBlock(): array
    {
        return [
            'faction_id' => $this->faker->uuid,
            'leader' => $this->faker->uuid,
            'avatar' => $this->faker->imageUrl,
            'roster' => [
                $this->playerBlock(),
                $this->playerBlock(),
                $this->playerBlock(),
                $this->playerBlock()
            ],
            'substituted' => false,
            'name' => $this->faker->word,
            'type' => 'premade'
        ];
    }
}

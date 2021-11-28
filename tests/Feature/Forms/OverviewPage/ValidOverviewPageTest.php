<?php
declare(strict_types=1);

namespace Tests\Feature\Forms\OverviewPage;

use App\Http\Livewire\OverviewPage;
use App\Models\Player;
use App\Models\ServiceRecord;
use Livewire\Livewire;
use Tests\TestCase;

class ValidOverviewPageTest extends TestCase
{
    /** @dataProvider validAttributesDataProvider */
    public function testValidResponseFromHaloDotApi(array $attributes): void
    {
        // Arrange
        $player = Player::factory()
            ->has(ServiceRecord::factory()->state($attributes))
            ->createOne();

        $serviceRecord = $player->serviceRecord;

        // Act & Assert
        Livewire::test(OverviewPage::class, [
            'player' => $player
        ])
            ->assertViewHas('serviceRecord')
            ->assertSee('Quick Peek')
            ->assertSee('Overall')
            ->assertSee('Types of Kills')
            ->assertSee('Other')
            ->assertSee($serviceRecord->kd);
    }

    public function testValidPrivateResponseFromHaloDotApi(): void
    {
        // Arrange
        $player = Player::factory()
            ->has(ServiceRecord::factory())
            ->createOne([
                'is_private' => true
            ]);

        // Act & Assert
        Livewire::test(OverviewPage::class, [
            'player' => $player
        ])
            ->call('render')
            ->assertViewHas('serviceRecord')
            ->assertSee('Warning - Account Private')
            ->assertDontSee('Quick Peek')
            ->assertDontSee('Overall')
            ->assertDontSee('Types of Kills')
            ->assertDontSee('Other');
    }

    public function validAttributesDataProvider(): array
    {
        return [
            'positive kd' => [
                'attributes' => [
                    'kd' => 1.5,
                    'kda' => 2.5,
                    'accuracy' => 60
                ]
            ],
            'negative kd' => [
                'attributes' => [
                    'kd' => 0.32,
                    'kda' => 1.32,
                    'accuracy' => 41
                ]
            ],
            'alright kd' => [
                'attributes' => [
                    'kd' => 0.99,
                    'kda' => 0.99,
                    'accuracy' => 21
                ]
            ],
            'positive win rate' => [
                'attributes' => [
                    'matches_won' => 50,
                    'total_matches' => 51
                ]
            ],
            'okay win rate' => [
                'attributes' => [
                    'matches_won' => 5,
                    'total_matches' => 10
                ]
            ],
            'poor win rate' => [
                'attributes' => [
                    'matches_won' => 0,
                    'total_matches' => 5
                ]
            ],
            'horrible accuracy' => [
                'attributes' => [
                    'accuracy' => 15,
                ]
            ],
        ];
    }
}

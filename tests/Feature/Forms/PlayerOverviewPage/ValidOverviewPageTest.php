<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\PlayerOverviewPage;

use App\Enums\Mode;
use App\Livewire\PlayerOverviewPage;
use App\Models\Player;
use App\Models\ServiceRecord;
use App\Support\Session\ModeSession;
use App\Support\Session\SeasonSession;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ValidOverviewPageTest extends TestCase
{
    #[DataProvider('validAttributesDataProvider')]
    public function test_valid_response_from_dot_api(array $attributes, ?Mode $mode): void
    {
        // Arrange
        $attributes['mode'] = $mode->value ?? Mode::MATCHMADE_RANKED;
        $serviceRecordType = $mode?->toPlayerRelation() ?? 'serviceRecord';

        $player = Player::factory()
            ->has(ServiceRecord::factory()->state($attributes))
            ->createOne();

        $serviceRecord = $player->$serviceRecordType;

        // Act & Assert
        ModeSession::set($attributes['mode']);
        SeasonSession::set('1-1');
        Livewire::test(PlayerOverviewPage::class, [
            'player' => $player,
        ])
            ->assertViewHas('serviceRecord')
            ->assertSee('Quick Peek')
            ->assertSee('Overall')
            ->assertSee('Types of Kills')
            ->assertSee('Other')
            ->assertSee($serviceRecord->kd);
    }

    public function test_valid_private_response_from_dot_api(): void
    {
        // Arrange
        $player = Player::factory()
            ->has(ServiceRecord::factory())
            ->createOne([
                'is_private' => true,
            ]);

        // Act & Assert
        ModeSession::set(Mode::MATCHMADE_RANKED);
        Livewire::test(PlayerOverviewPage::class, [
            'player' => $player,
        ])
            ->assertViewHas('serviceRecord')
            ->assertSee('Warning - Account Private')
            ->assertDontSee('Quick Peek')
            ->assertDontSee('Overall')
            ->assertDontSee('Types of Kills')
            ->assertDontSee('Other');
    }

    public static function validAttributesDataProvider(): array
    {
        return [
            'positive kd' => [
                'attributes' => [
                    'kd' => 1.5,
                    'kda' => 2.5,
                    'accuracy' => 60,
                ],
                'mode' => null,
            ],
            'negative kd' => [
                'attributes' => [
                    'kd' => 0.32,
                    'kda' => 1.32,
                    'accuracy' => 41,
                ],
                'mode' => Mode::MATCHMADE_RANKED(),
            ],
            'alright kd' => [
                'attributes' => [
                    'kd' => 0.99,
                    'kda' => 0.99,
                    'accuracy' => 21,
                ],
                'mode' => Mode::MATCHMADE_RANKED(),
            ],
            'positive win rate' => [
                'attributes' => [
                    'matches_won' => 50,
                    'total_matches' => 51,
                ],
                'mode' => Mode::MATCHMADE_PVP(),
            ],
            'okay win rate' => [
                'attributes' => [
                    'matches_won' => 5,
                    'total_matches' => 10,
                ],
                'mode' => Mode::MATCHMADE_PVP(),
            ],
            'poor win rate' => [
                'attributes' => [
                    'matches_won' => 0,
                    'total_matches' => 5,
                ],
                'mode' => Mode::MATCHMADE_PVP(),
            ],
            'horrible accuracy' => [
                'attributes' => [
                    'accuracy' => 15,
                ],
                'mode' => Mode::MATCHMADE_PVP(),
            ],
            '0 wins' => [
                'attributes' => [
                    'matches_won' => 1,
                    'total_matches' => 0,
                ],
                'mode' => null,
            ],
        ];
    }
}

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
    public function testValidResponseFromHaloDotApi(): void
    {
        // Arrange
        $player = Player::factory()
            ->has(ServiceRecord::factory())
            ->createOne();

        $serviceRecord = $player->serviceRecord;

        // Act & Assert
        Livewire::test(OverviewPage::class, [
            'player' => $player
        ])
            ->call('render')
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
}

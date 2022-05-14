<?php
declare(strict_types=1);

namespace Tests\Feature\Forms\PlayerTogglePanel;

use App\Enums\Mode;
use App\Http\Livewire\PlayerTogglePanel;
use App\Support\Session\ModeSession;
use App\Support\Session\SeasonSession;
use Livewire\Livewire;
use Tests\TestCase;

class ValidPlayerToggleTest extends TestCase
{
    public function testValidResponseFromModeChange(): void
    {
        // Arrange

        // Act & Assert
        Livewire::test(PlayerTogglePanel::class, [
            'playerType' => Mode::MATCHMADE_PVP,
        ])
            ->call('onChange')
            ->assertEmittedTo('overview-page', '$refresh')
            ->assertEmittedTo('medals-page', '$refresh');

        $mode = ModeSession::get();
        $this->assertEquals(Mode::MATCHMADE_PVP(), $mode);
    }

    public function testValidResponseFromSeasonChange(): void
    {
        // Arrange

        // Act & Assert
        Livewire::test(PlayerTogglePanel::class)
            ->set('season', 1)
            ->call('onSeasonChange')
            ->assertEmittedTo('overview-page', '$refresh')
            ->assertEmittedTo('medals-page', '$refresh');

        $season = SeasonSession::get();
        $this->assertEquals(1, $season);
    }
}

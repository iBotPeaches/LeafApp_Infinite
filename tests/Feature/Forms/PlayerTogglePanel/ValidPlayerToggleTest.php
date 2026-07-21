<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\PlayerTogglePanel;

use App\Enums\Mode;
use App\Livewire\PlayerTogglePanel;
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
            ->assertDispatchedTo('overview-page', '$refresh')
            ->assertDispatchedTo('medals-page', '$refresh');

        $mode = ModeSession::get();
        $this->assertEquals(Mode::MATCHMADE_PVP(), $mode);
    }

    public function testValidResponseFromSeasonChange(): void
    {
        // Arrange

        // Act & Assert
        Livewire::test(PlayerTogglePanel::class)
            ->set('seasonKey', '1-1')
            ->call('onSeasonChange')
            ->assertDispatchedTo('overview-page', '$refresh')
            ->assertDispatchedTo('medals-page', '$refresh');

        $this->assertEquals('1-1', SeasonSession::get());
    }
}

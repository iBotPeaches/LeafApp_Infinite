<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\OverviewTogglePanel;

use App\Livewire\OverviewMatchesTable;
use App\Livewire\OverviewOverview;
use App\Livewire\OverviewTogglePanel;
use App\Models\Overview;
use App\Support\Session\OverviewGametypeSession;
use App\Support\Session\OverviewMapSession;
use Livewire\Livewire;
use Tests\TestCase;

class ValidOverviewToggleTest extends TestCase
{
    public function test_valid_response_from_map_change(): void
    {
        // Arrange
        $overview = Overview::factory()->createOne();

        // Act & Assert
        Livewire::test(OverviewTogglePanel::class, [
            'overview' => $overview,
        ])
            ->set('mapId', 41)
            ->call('onMapChange')
            ->assertDispatchedTo(OverviewMatchesTable::class, '$refresh')
            ->assertDispatchedTo(OverviewOverview::class, '$refresh');

        $mapId = OverviewMapSession::get($overview);
        $this->assertEquals(41, $mapId);
    }

    public function test_valid_response_from_gametype_change(): void
    {
        // Arrange
        $overview = Overview::factory()->createOne();

        // Act & Assert
        Livewire::test(OverviewTogglePanel::class, [
            'overview' => $overview,
        ])
            ->set('gametypeId', 31)
            ->call('onGametypeChange')
            ->assertDispatchedTo(OverviewMatchesTable::class, '$refresh')
            ->assertDispatchedTo(OverviewOverview::class, '$refresh');

        $gametypeId = OverviewGametypeSession::get($overview);
        $this->assertEquals(31, $gametypeId);
    }
}

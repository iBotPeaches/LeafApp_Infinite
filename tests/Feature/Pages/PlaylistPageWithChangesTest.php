<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\Playlist;
use App\Models\PlaylistChange;
use Carbon\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class PlaylistPageWithChangesTest extends TestCase
{
    public function test_playlist_page_displays_changes_when_previous_rotation_exists(): void
    {
        // Arrange
        $playlist = Playlist::factory()->create([
            'rotations' => [
                ['name' => 'Arena:Slayer on Aquarius', 'weight' => 75],
                ['name' => 'Arena:Slayer on Bazaar', 'weight' => 25],
            ],
        ]);

        // Create a previous rotation
        PlaylistChange::factory()->create([
            'playlist_id' => $playlist->id,
            'rotation_hash' => 'different_hash',
            'rotations' => [
                ['name' => 'Arena:Slayer on Aquarius', 'weight' => 25],
                ['name' => 'Arena:Slayer on Recharge', 'weight' => 75],
            ],
            'created_at' => Carbon::now()->subDays(7),
        ]);

        // Act
        $component = Livewire::test('playlist-page', ['playlist' => $playlist]);

        // Assert
        $component->assertSee('Aquarius');
        $component->assertSee('Bazaar');
        $component->assertSee('Change'); // Change column should be visible
        
        // Should show dates
        $component->assertSee('Current rotation:');
        $component->assertSee('Previous rotation:');
    }

    public function test_playlist_page_hides_changes_when_no_previous_rotation(): void
    {
        // Arrange
        $playlist = Playlist::factory()->create([
            'rotations' => [
                ['name' => 'Arena:Slayer on Aquarius', 'weight' => 100],
            ],
        ]);

        // Act
        $component = Livewire::test('playlist-page', ['playlist' => $playlist]);

        // Assert
        $component->assertSee('Aquarius');
        $component->assertDontSee('Change'); // Change column should not be visible
        $component->assertDontSee('Current rotation:'); // Dates should not be visible
    }
}
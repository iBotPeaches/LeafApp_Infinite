<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\Playlist;
use App\Models\PlaylistAnalytic;
use App\Models\PlaylistChange;
use App\Models\PlaylistStat;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PlaylistPageTest extends TestCase
{
    public function test_example_playlist_loading_if_none(): void
    {
        // Arrange & Act
        $response = $this->get('/playlists');

        // Assert
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_example_playlist_loading(): void
    {
        // Arrange
        Playlist::factory()->createOne();

        // Act
        $response = $this->get('/playlists');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_example_playlist_loading_specific_name(): void
    {
        // Arrange
        /** @var Playlist $playlist */
        $playlist = Playlist::factory()->createOne([
            'name' => 'Rumble Pit',
        ]);

        // Act
        $response = $this->get('/playlists/'.$playlist->uuid);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_playlist_loading_stats(): void
    {
        // Arrange
        $playlist = Playlist::factory()
            ->has(PlaylistStat::factory(), 'stat')
            ->has(PlaylistAnalytic::factory(), 'analytics')
            ->create();

        // Response
        $response = $this->get('/playlists/'.$playlist->uuid.'/stats');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_playlist_loading_historic(): void
    {
        // Arrange
        $playlist = Playlist::factory()
            ->has(PlaylistChange::factory()->count(2), 'changes')
            ->create();

        // Act
        $response = $this->get('/playlists/'.$playlist->uuid.'/historic');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_playlist_loading_historic_empty(): void
    {
        // Arrange
        $playlist = Playlist::factory()->create();

        // Act
        $response = $this->get('/playlists/'.$playlist->uuid.'/historic');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_playlist_overview_with_previous_rotation(): void
    {
        // Arrange
        $playlist = Playlist::factory()->create();

        // Previous rotation: has "Arena:Oddball on Map" (will be removed) and different weight for Slayer
        PlaylistChange::factory()->create([
            'playlist_id' => $playlist->id,
            'created_at' => now()->subDay(),
            'rotations' => [
                ['name' => 'Arena:Slayer on Map', 'weight' => 50],
                ['name' => 'Arena:CTF on Map', 'weight' => 110],
                ['name' => 'Arena:Oddball on Map', 'weight' => 40],
            ],
        ]);

        // Current rotation: has "Arena:Strongholds on Map" (added), Slayer weight changed, Oddball removed
        PlaylistChange::factory()->create([
            'playlist_id' => $playlist->id,
            'created_at' => now(),
            'rotations' => [
                ['name' => 'Arena:Slayer on Map', 'weight' => 100],
                ['name' => 'Arena:CTF on Map', 'weight' => 110],
                ['name' => 'Arena:Strongholds on Map', 'weight' => 90],
            ],
        ]);

        // Act
        $response = $this->get('/playlists/'.$playlist->uuid.'/overview');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }
}

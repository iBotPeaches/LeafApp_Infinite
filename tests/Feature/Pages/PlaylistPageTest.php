<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\Playlist;
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
}

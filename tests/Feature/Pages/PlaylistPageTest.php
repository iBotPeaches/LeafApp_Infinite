<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\Playlist;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PlaylistPageTest extends TestCase
{
    public function testExamplePlaylistLoadingIfNone(): void
    {
        // Arrange & Act
        $response = $this->get('/playlists');

        // Assert
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testExamplePlaylistLoading(): void
    {
        // Arrange
        Playlist::factory()->createOne();

        // Act
        $response = $this->get('/playlists');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testExamplePlaylistLoadingSpecificName(): void
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

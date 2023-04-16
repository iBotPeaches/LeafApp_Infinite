<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Enums\MedalDifficulty;
use App\Enums\MedalType;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Metadata\MockCategoriesService;
use Tests\Mocks\Metadata\MockMapsService;
use Tests\Mocks\Metadata\MockMedalsService;
use Tests\Mocks\Metadata\MockPlaylistsService;
use Tests\Mocks\Metadata\MockSeasonService;
use Tests\Mocks\Metadata\MockTeamsService;
use Tests\TestCase;

class PullMetadataTest extends TestCase
{
    use WithFaker;

    public function testValidDataPull(): void
    {
        // Arrange
        $mockMedalsResponse = (new MockMedalsService())->success();
        $mockMapsResponse = (new MockMapsService())->success();
        $mockTeamsResponse = (new MockTeamsService())->success();
        $mockPlaylistResponse = (new MockPlaylistsService())->success();
        $mockCategoriesResponse = (new MockCategoriesService())->success();
        $mockSeasonsResponse = (new MockSeasonService())->success();

        Arr::set($mockMedalsResponse, 'data.2.category', MedalType::MODE);
        Arr::set($mockMedalsResponse, 'data.3.type', MedalDifficulty::LEGENDARY);

        Http::fakeSequence()
            ->push($mockMedalsResponse, Response::HTTP_OK)
            ->push($mockMapsResponse, Response::HTTP_OK)
            ->push($mockTeamsResponse, Response::HTTP_OK)
            ->push($mockPlaylistResponse, Response::HTTP_OK)
            ->push($mockCategoriesResponse, Response::HTTP_OK)
            ->push($mockSeasonsResponse, Response::HTTP_OK);

        // Act & Assert
        $this
            ->artisan('app:pull-metadata')
            ->assertExitCode(CommandAlias::SUCCESS);
    }

    public function testInvalidPullNewType(): void
    {
        // Expectations
        $this->expectException(\InvalidArgumentException::class);

        // Arrange
        $mockMedalsResponse = (new MockMedalsService())->success();

        Arr::set($mockMedalsResponse, 'data.0.properties.type', 'invalid-category');
        Http::fakeSequence()->push($mockMedalsResponse, Response::HTTP_OK);

        // Act & Assert
        $this
            ->artisan('app:pull-metadata')
            ->assertExitCode(CommandAlias::FAILURE);
    }

    public function testInvalidInputForPlaylist(): void
    {
        // Expectations
        $this->expectException(\InvalidArgumentException::class);

        // Arrange
        $mockMedalsResponse = (new MockMedalsService())->success();
        $mockMapsResponse = (new MockMapsService())->success();
        $mockTeamsResponse = (new MockTeamsService())->success();
        $mockPlaylistResponse = (new MockPlaylistsService())->success();

        Arr::set($mockPlaylistResponse, 'data.0.properties.input', 'unknown-input');

        Http::fakeSequence()
            ->push($mockMedalsResponse, Response::HTTP_OK)
            ->push($mockMapsResponse, Response::HTTP_OK)
            ->push($mockTeamsResponse, Response::HTTP_OK)
            ->push($mockPlaylistResponse, Response::HTTP_OK);

        // Act & Assert
        $this
            ->artisan('app:pull-metadata')
            ->assertExitCode(CommandAlias::FAILURE);
    }

    public function testInvalidQueueForPlaylist(): void
    {
        // Expectations
        $this->expectException(\InvalidArgumentException::class);

        // Arrange
        $mockMedalsResponse = (new MockMedalsService())->success();
        $mockMapsResponse = (new MockMapsService())->success();
        $mockTeamsResponse = (new MockTeamsService())->success();
        $mockPlaylistResponse = (new MockPlaylistsService())->success();

        Arr::set($mockPlaylistResponse, 'data.0.properties.queue', 'unknown-queue');

        Http::fakeSequence()
            ->push($mockMedalsResponse, Response::HTTP_OK)
            ->push($mockMapsResponse, Response::HTTP_OK)
            ->push($mockTeamsResponse, Response::HTTP_OK)
            ->push($mockPlaylistResponse, Response::HTTP_OK);

        // Act & Assert
        $this
            ->artisan('app:pull-metadata')
            ->assertExitCode(CommandAlias::FAILURE);
    }

    public function testInvalidPullNewDifficulty(): void
    {
        // Expectations
        $this->expectException(\InvalidArgumentException::class);

        // Arrange
        $mockMedalsResponse = (new MockMedalsService())->success();

        Arr::set($mockMedalsResponse, 'data.0.attributes.difficulty', 'invalid-type');
        Http::fakeSequence()->push($mockMedalsResponse, Response::HTTP_OK);

        // Act & Assert
        $this
            ->artisan('app:pull-metadata')
            ->assertExitCode(CommandAlias::FAILURE);
    }
}

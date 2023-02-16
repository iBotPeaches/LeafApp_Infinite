<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Jobs\FindPlayersFromTeam;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Championship\MockChampionshipBracketService;
use Tests\Mocks\Championship\MockChampionshipService;
use Tests\TestCase;

class PullChampionshipTest extends TestCase
{
    use WithFaker;

    /** @dataProvider championshipTypeDataProvider */
    public function testValidDataPull(string $type): void
    {
        // Arrange
        Queue::fake();
        $championshipId = $this->faker->uuid;
        $mockChampionshipResponse = (new MockChampionshipService())->success();
        $mockChampionshipBracketResponse = (new MockChampionshipBracketService())->success();
        $mockChampionshipBracketEmpty = (new MockChampionshipBracketService())->empty();

        Arr::set($mockChampionshipResponse, 'type', $type);

        Http::fakeSequence()
            ->push($mockChampionshipResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketEmpty, Response::HTTP_OK);

        // Act & Assert
        $this
            ->artisan('app:championship '.$championshipId)
            ->assertExitCode(CommandAlias::SUCCESS);

        Queue::assertPushed(FindPlayersFromTeam::class);
    }

    public function testValidDataPullAsFfa(): void
    {
        // Arrange
        Queue::fake();
        $championshipId = $this->faker->uuid;
        $mockChampionshipResponse = (new MockChampionshipService())->success();
        $mockChampionshipBracketResponse = (new MockChampionshipBracketService())->success();
        $mockChampionshipBracketEmpty = (new MockChampionshipBracketService())->empty();

        Arr::set($mockChampionshipResponse, 'type', 'stage');
        // TODO - Make setting players per team easier, this changes 4 to 1.
        $match1Player = Arr::get($mockChampionshipBracketResponse, 'items.0.teams.faction1.roster.0');
        Arr::set($mockChampionshipBracketResponse, 'items.0.teams.faction1.roster', []);
        Arr::set($mockChampionshipBracketResponse, 'items.0.teams.faction1.roster.0', $match1Player);

        Http::fakeSequence()
            ->push($mockChampionshipResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketEmpty, Response::HTTP_OK);

        // Act & Assert
        $this
            ->artisan('app:championship '.$championshipId)
            ->assertExitCode(CommandAlias::SUCCESS);

        Queue::assertPushed(FindPlayersFromTeam::class);
    }

    public function testValidDataPullWithInvalidType(): void
    {
        // Expectations
        $this->expectException(InvalidArgumentException::class);

        // Arrange
        $championshipId = $this->faker->uuid;
        $mockChampionshipResponse = (new MockChampionshipService())->success();
        $mockChampionshipBracketResponse = (new MockChampionshipBracketService())->success();
        $mockChampionshipBracketEmpty = (new MockChampionshipBracketService())->empty();

        Arr::set($mockChampionshipResponse, 'type', 'INVALID-TYPE');

        Http::fakeSequence()
            ->push($mockChampionshipResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketEmpty, Response::HTTP_OK);

        // Act & Assert
        $this->artisan('app:championship '.$championshipId)
            ->assertExitCode(CommandAlias::FAILURE);
    }

    public function testValidDataPullWithInvalidRegion(): void
    {
        // Expectations
        $this->expectException(InvalidArgumentException::class);

        // Arrange
        $championshipId = $this->faker->uuid;
        $mockChampionshipResponse = (new MockChampionshipService())->success();
        $mockChampionshipBracketResponse = (new MockChampionshipBracketService())->success();
        $mockChampionshipBracketEmpty = (new MockChampionshipBracketService())->empty();

        Arr::set($mockChampionshipResponse, 'region', 'INVALID-ENUM');

        Http::fakeSequence()
            ->push($mockChampionshipResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketEmpty, Response::HTTP_OK);

        // Act & Assert
        $this->artisan('app:championship '.$championshipId)
            ->assertExitCode(CommandAlias::FAILURE);
    }

    public function championshipTypeDataProvider(): array
    {
        return [
            [
                'type' => 'roundRobin',
            ],
            [
                'type' => 'doubleElimination',
            ],
            [
                'type' => 'stage',
            ],
        ];
    }
}

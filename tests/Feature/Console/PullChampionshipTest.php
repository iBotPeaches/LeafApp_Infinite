<?php
declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Jobs\FindPlayersFromTeam;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Championship\MockChampionshipBracketService;
use Tests\Mocks\Championship\MockChampionshipService;
use Tests\TestCase;

class PullChampionshipTest extends TestCase
{
    use WithFaker;

    public function testValidDataPull(): void
    {
        // Arrange
        Queue::fake();
        $championshipId = $this->faker->uuid;
        $mockChampionshipResponse = (new MockChampionshipService())->success();
        $mockChampionshipBracketResponse = (new MockChampionshipBracketService())->success();
        $mockChampionshipBracketEmpty = (new MockChampionshipBracketService())->empty();

        Http::fakeSequence()
            ->push($mockChampionshipResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketResponse, Response::HTTP_OK)
            ->push($mockChampionshipBracketEmpty, Response::HTTP_OK);

        // Act & Assert
        $this
            ->artisan('app:championship ' . $championshipId)
            ->assertExitCode(CommandAlias::SUCCESS);

        Queue::assertPushed(FindPlayersFromTeam::class);
    }
}

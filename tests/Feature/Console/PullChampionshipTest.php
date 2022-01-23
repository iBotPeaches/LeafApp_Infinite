<?php
declare(strict_types=1);

namespace Tests\Feature\Console;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Championship\MockChampionshipService;
use Tests\TestCase;

class PullChampionshipTest extends TestCase
{
    use WithFaker;

    public function testValidDataPull(): void
    {
        // Arrange
        $championshipId = $this->faker->uuid;
        $mockChampshionResponse = (new MockChampionshipService())->success();

        Http::fakeSequence()->push($mockChampshionResponse, Response::HTTP_OK);

        // Act & Assert
        $this
            ->artisan('app:championship ' . $championshipId)
            ->assertExitCode(CommandAlias::SUCCESS);
    }
}

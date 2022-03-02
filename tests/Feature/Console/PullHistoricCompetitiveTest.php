<?php
declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Models\Player;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Csrs\MockCsrAllService;
use Tests\TestCase;

class PullHistoricCompetitiveTest extends TestCase
{
    use WithFaker;

    public function testValidDataPull(): void
    {
        // Arrange
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService())->success($gamertag);

        Http::fakeSequence()
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockCsrResponse, Response::HTTP_OK);

        Player::factory()->createOne([
            'gamertag' => $gamertag,
            'last_csr_key' => null,
        ]);

        // Act & Assert
        $this
            ->artisan('app:pull-historic-competitive')
            ->assertExitCode(CommandAlias::SUCCESS);

        $this->assertDatabaseHas('players', [
            'gamertag' => $gamertag,
            'last_csr_key' => config('services.autocode.competitive.season')
                . config('services.autocode.competitive.version')
        ]);
    }
}

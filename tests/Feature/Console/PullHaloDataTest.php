<?php
declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Models\Player;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Csrs\MockCsrAllService;
use Tests\Mocks\Matches\MockMatchesService;
use Tests\Mocks\ServiceRecord\MockServiceRecordService;
use Tests\TestCase;

class PullHaloDataTest extends TestCase
{
    use WithFaker;

    public function testInvalidGamertag(): void
    {
        $this
            ->artisan('app:pull-halo-data', ['player' => '999999999'])
            ->assertExitCode(CommandAlias::FAILURE);
    }

    public function testValidDataPull(): void
    {
        // Arrange
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockCsrResponse = (new MockCsrAllService())->success($gamertag);
        $mockMatchesResponse = (new MockMatchesService())->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockServiceResponse = (new MockServiceRecordService())->success($gamertag);

        Http::fakeSequence()
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag
        ]);

        // Act & Assert
        $this
            ->artisan('app:pull-halo-data', ['player' => $player->id])
            ->assertExitCode(CommandAlias::SUCCESS);
    }
}

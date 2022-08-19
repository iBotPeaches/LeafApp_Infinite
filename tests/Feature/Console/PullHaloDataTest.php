<?php
declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Models\Game;
use App\Models\Player;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Csrs\MockCsrAllService;
use Tests\Mocks\Matches\MockMatchesService;
use Tests\Mocks\Matches\MockMatchService;
use Tests\Mocks\Mmr\MockMmrService;
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
        $mockMmrResponse = (new MockMmrService())->success($gamertag);
        $mockMatchResponse = (new MockMatchService())->success($gamertag, $gamertag);
        $mockMatchesResponse = (new MockMatchesService())->success($gamertag);
        $mockEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockCustomMatchesResponse = (new MockMatchesService())->success($gamertag);
        $mockCustomEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockLanMatchesResponse = (new MockMatchesService())->success($gamertag);
        $mockLanEmptyMatchesResponse = (new MockMatchesService())->empty($gamertag);
        $mockServiceResponse = (new MockServiceRecordService())->success($gamertag);

        Arr::set($mockCustomMatchesResponse, 'data.0.details.playlist', null);
        Arr::set($mockCustomMatchesResponse, 'data.1.details.playlist', null);

        $matchmakingGameUuid = Arr::get($mockMatchesResponse, 'data.0.id');
        $customGameUuid = Arr::get($mockCustomMatchesResponse, 'data.0.id');
        $lanGameUuid = Arr::get($mockLanMatchesResponse, 'data.0.id');

        Http::fakeSequence()
            ->push($mockCsrResponse, Response::HTTP_OK)
            ->push($mockMmrResponse, Response::HTTP_OK)
            ->push($mockMatchResponse, Response::HTTP_OK)
            ->push($mockMatchesResponse, Response::HTTP_OK)
            ->push($mockEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockCustomMatchesResponse, Response::HTTP_OK)
            ->push($mockCustomEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockLanMatchesResponse, Response::HTTP_OK)
            ->push($mockLanEmptyMatchesResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK)
            ->push($mockServiceResponse, Response::HTTP_OK);

        $player = Player::factory()->createOne([
            'gamertag' => $gamertag
        ]);

        // Act & Assert
        $this
            ->artisan('app:pull-halo-data', ['player' => $player->id])
            ->assertExitCode(CommandAlias::SUCCESS);

        $lastMatchmakingGame = Game::query()->firstWhere('uuid', $matchmakingGameUuid);
        $lastCustomGame = Game::query()->firstWhere('uuid', $customGameUuid);
        $lastLanGame = Game::query()->firstWhere('uuid', $lanGameUuid);

        $this->assertDatabaseHas('players', [
            'gamertag' => $gamertag,
            'last_game_id_pulled' => $lastMatchmakingGame?->id,
            'last_custom_game_id_pulled' => $lastCustomGame?->id,
            'last_lan_game_id_pulled' => $lastLanGame?->id
        ]);
    }
}

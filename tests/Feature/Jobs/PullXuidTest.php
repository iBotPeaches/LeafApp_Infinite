<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Jobs\PullXuid;
use App\Models\Player;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Xuid\MockXuidService;
use Tests\TestCase;

class PullXuidTest extends TestCase
{
    use WithFaker;

    public function testDeletingFailedXuidJob(): void
    {
        // Arrange
        $mockXuidResponse = (new MockXuidService())->success('gamertag');

        Http::fakeSequence()
            ->push($mockXuidResponse, Response::HTTP_INTERNAL_SERVER_ERROR);

        $player = Player::factory()->createOne(['xuid' => null]);

        // Act
        PullXuid::dispatchSync($player);

        // Assert
        $this->assertDatabaseHas('players', [
            'id' => $player->id,
            'xuid' => null,
        ]);
    }
}

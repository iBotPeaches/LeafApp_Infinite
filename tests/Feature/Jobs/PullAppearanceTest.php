<?php
declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Jobs\PullAppearance;
use App\Models\Player;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Appearance\MockAppearanceService;
use Tests\Mocks\Image\MockImageService;
use Tests\TestCase;

class PullAppearanceTest extends TestCase
{
    use WithFaker;

    public function testPullingAssetsDownFromWeb(): void
    {
        // Arrange
        $mockAppearanceResponse = (new MockAppearanceService())->success('gamertag');
        $mockOptimizedResponse = (new MockImageService())->success();

        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockOptimizedResponse, Response::HTTP_OK);

        $player = Player::factory()->createOne();

        // Act
        PullAppearance::dispatchSync($player);

        // Assert
        $this->assertDatabaseHas('players', [
            'id' => $player->id,
        ]);
    }
}

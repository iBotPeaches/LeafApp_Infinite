<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Adapters\FileUtilInterface;
use App\Jobs\PullAppearance;
use App\Jobs\PullXuid;
use App\Models\Player;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Appearance\MockAppearanceService;
use Tests\Mocks\Image\MockImageService;
use Tests\TestCase;

class PullAppearanceTest extends TestCase
{
    use WithFaker;

    public function testPullAppearanceAsBot(): void
    {
        // Arrange
        Http::fake()->preventStrayRequests();

        $player = Player::factory()->createOne([
            'is_bot' => true,
        ]);

        // Act
        PullAppearance::dispatchSync($player);

        // Assert
        $this->assertDatabaseHas('players', [
            'id' => $player->id,
            'is_bot' => true,
        ]);
    }

    public function testPullingAssetsDownFromWeb(): void
    {
        // Arrange
        Bus::fake([
            PullXuid::class,
        ]);
        Storage::fake();
        $mockAppearanceResponse = (new MockAppearanceService())->success('gamertag');
        $mockOptimizedResponse = (new MockImageService())->success();

        $this->instance(
            FileUtilInterface::class,
            Mockery::mock(FileUtilInterface::class, function (Mockery\MockInterface $mock) {
                $mock
                    ->shouldReceive('getFileContents')
                    ->andReturn('example-binary-contents');
            })
        );

        $headers = ['Location' => 'domain.com'];
        Http::fakeSequence()
            ->push($mockAppearanceResponse, Response::HTTP_OK)
            ->push($mockOptimizedResponse, Response::HTTP_OK, $headers)
            ->push($mockOptimizedResponse, Response::HTTP_OK, $headers);

        $player = Player::factory()->createOne();

        // Act
        PullAppearance::dispatchSync($player);

        // Assert
        $this->assertDatabaseHas('players', [
            'id' => $player->id,
        ]);
        Bus::assertDispatched(PullXuid::class);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Adapters\FileUtilInterface;
use App\Models\Overview;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Image\MockImageService;
use Tests\TestCase;

class PullOverviewImagesTest extends TestCase
{
    use WithFaker;

    public function testDownloadOfMapImages(): void
    {
        // Arrange
        Http::preventStrayRequests();
        Storage::fake();

        $this->instance(
            FileUtilInterface::class,
            Mockery::mock(FileUtilInterface::class, function (Mockery\MockInterface $mock) {
                $mock
                    ->shouldReceive('getFileContents')
                    ->andReturn('example-binary-contents');
            })
        );

        $mockOptimizedResponse = (new MockImageService())->success();
        $headers = ['Location' => 'domain.com'];
        Http::fakeSequence()
            ->push(null, Response::HTTP_OK)
            ->push($mockOptimizedResponse, Response::HTTP_OK, $headers);

        Overview::factory()->createOne();

        // Act
        $this->artisan('app:pull-overview-images');

        // Assert
        Http::assertSequencesAreEmpty();
    }
}

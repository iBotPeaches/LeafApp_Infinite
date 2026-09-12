<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Adapters\FileUtilInterface;
use App\Models\Overview;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use JMac\Testing\Double;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Image\MockImageService;
use Tests\TestCase;

class PullOverviewImagesTest extends TestCase
{
    use WithFaker;

    public function test_download_of_map_images(): void
    {
        // Arrange
        Http::preventStrayRequests();
        Storage::fake();

        $fileUtil = Double::for(FileUtilInterface::class);
        $fileUtil->allows('getFileContents')->returns('example-binary-contents');
        $this->instance(FileUtilInterface::class, $fileUtil);

        $mockOptimizedResponse = (new MockImageService)->success();
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

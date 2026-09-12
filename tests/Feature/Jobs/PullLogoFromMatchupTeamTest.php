<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Adapters\FileUtilInterface;
use App\Jobs\PullLogoFromMatchupTeam;
use App\Models\MatchupTeam;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use JMac\Testing\Double;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Image\MockImageService;
use Tests\TestCase;

class PullLogoFromMatchupTeamTest extends TestCase
{
    use WithFaker;

    public function test_pulling_logo_from_team(): void
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
            ->push($mockOptimizedResponse, Response::HTTP_OK, $headers);

        /** @var MatchupTeam $matchupTeam */
        $matchupTeam = MatchupTeam::factory()->createOne();

        // Act
        PullLogoFromMatchupTeam::dispatchSync($matchupTeam, 'http://example.com');

        // Assert
        $this->assertNotNull($matchupTeam->avatar);
        Http::assertSequencesAreEmpty();
    }
}

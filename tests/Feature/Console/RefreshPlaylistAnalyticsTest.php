<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Jobs\ProcessPlaylistAnalytic;
use App\Models\Playlist;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Tests\TestCase;

class RefreshPlaylistAnalyticsTest extends TestCase
{
    use WithFaker;

    public function test_valid_dispatch_of_jobs(): void
    {
        // Arrange
        Queue::fake();
        Playlist::factory()->create();

        // Act
        $this
            ->artisan('analytics:playlists:refresh')
            ->assertExitCode(CommandAlias::SUCCESS);

        // Assert
        Queue::assertPushed(ProcessPlaylistAnalytic::class);
    }
}

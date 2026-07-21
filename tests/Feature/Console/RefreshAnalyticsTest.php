<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Jobs\ProcessAnalytic;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Tests\TestCase;

class RefreshAnalyticsTest extends TestCase
{
    use WithFaker;

    public function testValidDispatchOfJobs(): void
    {
        // Arrange
        Queue::fake();

        // Act
        $this
            ->artisan('analytics:refresh')
            ->assertExitCode(CommandAlias::SUCCESS);

        // Assert
        Queue::assertPushed(ProcessAnalytic::class);
    }

    public function testValidDispatchOfSpecificJob(): void
    {
        // Arrange
        Queue::fake();

        // Act
        $this
            ->artisan('analytics:refresh', ['analytic' => 'MostXpPlayer'])
            ->assertExitCode(CommandAlias::SUCCESS);

        // Assert
        Queue::assertPushed(ProcessAnalytic::class);
    }
}

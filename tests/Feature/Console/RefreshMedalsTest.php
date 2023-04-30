<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Jobs\ProcessMedalAnalytic;
use App\Models\Medal;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Tests\TestCase;

class RefreshMedalsTest extends TestCase
{
    use WithFaker;

    public function testValidDispatchOfJobs(): void
    {
        // Arrange
        Queue::fake();
        Medal::factory()->createOne();

        // Act
        $this
            ->artisan('analytics:medals:refresh')
            ->assertExitCode(CommandAlias::SUCCESS);

        // Assert
        Queue::assertPushed(ProcessMedalAnalytic::class);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Jobs\ProcessMedalAnalytic;
use App\Models\Medal;
use App\Models\Season;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Tests\TestCase;

class RefreshMedalsTest extends TestCase
{
    use WithFaker;

    public function test_valid_dispatch_of_jobs(): void
    {
        // Arrange
        Bus::fake();
        Medal::factory()->createOne();
        Season::factory()->createOne();

        // Act
        $this
            ->artisan('analytics:medals:refresh')
            ->assertExitCode(CommandAlias::SUCCESS);

        // Assert
        Bus::assertDispatchedTimes(ProcessMedalAnalytic::class);
    }

    public function test_valid_dispatch_of_jobs_for_all(): void
    {
        // Arrange
        Bus::fake();
        Medal::factory()->createOne();
        Season::factory()->createOne();

        // Act
        $this
            ->artisan('analytics:medals:refresh --all')
            ->assertExitCode(CommandAlias::SUCCESS);

        // Assert
        Bus::assertDispatchedTimes(ProcessMedalAnalytic::class, 2);
    }
}

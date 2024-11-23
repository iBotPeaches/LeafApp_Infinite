<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Models\Overview;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RefreshManualOverviewsTest extends TestCase
{
    use WithFaker;

    public function test_valid_generation_of_overviews(): void
    {
        // Arrange
        $overview = Overview::factory()->createOne([
            'is_manual' => true,
        ]);

        // Act
        $this->artisan('analytics:overviews:manual-refresh')
            ->expectsOutputToContain('Processed '.$overview->name)
            ->assertOk();
    }
}

<?php

declare(strict_types=1);

namespace Feature\Forms\OverviewsTable;

use App\Livewire\OverviewsTable;
use App\Models\Overview;
use Livewire\Livewire;
use Tests\TestCase;

class ValidOverviewsListingTest extends TestCase
{
    public function test_valid_list_of_overviews(): void
    {
        // Arrange
        $overview = Overview::factory()->createOne();

        // Act & Assert
        Livewire::test(OverviewsTable::class, [
            'overviews' => collect([$overview]),
        ])
            ->assertViewHas('overviews')
            ->assertSee($overview->name)
            ->assertSeeHtml($overview->thumbnail_url);
    }
}

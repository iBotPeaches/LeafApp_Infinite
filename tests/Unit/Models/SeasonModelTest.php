<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Season;
use Tests\TestCase;

class SeasonModelTest extends TestCase
{
    public function testNullableLengthProperty(): void
    {
        // Arrange
        Season::factory()->createOne();

        // Act & Assert
        $this->assertNull(Season::ofSeasonIdentifierOrKey());
    }
}

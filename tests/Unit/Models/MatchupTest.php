<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Matchup;
use Tests\TestCase;

class MatchupTest extends TestCase
{
    public function testNullableLengthProperty(): void
    {
        // Arrange
        $matchup = Matchup::factory()
            ->createOne([
                'ended_at' => null,
            ]);

        // Act & Assert
        $this->assertNull($matchup->length);
    }
}

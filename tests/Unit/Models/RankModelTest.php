<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Rank;
use Tests\TestCase;

class RankModelTest extends TestCase
{
    public function testIconImage(): void
    {
        // Arrange
        $rank = Rank::factory()->createOne([
            'id' => 1,
        ]);

        // Act & Assert
        $this->assertEquals('/images/ranks/icons/1.png', $rank->icon);
    }
}

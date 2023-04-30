<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Medal;
use App\Models\MedalAnalytic;
use App\Models\Player;
use App\Models\Season;
use Tests\TestCase;

class MedalAnalyticTest extends TestCase
{
    public function testRelationProperties(): void
    {
        // Arrange
        /** @var MedalAnalytic $medalAnalytic */
        $medalAnalytic = MedalAnalytic::factory()->createOne();

        // Act & Assert
        $this->assertInstanceOf(Player::class, $medalAnalytic->player);
        $this->assertInstanceOf(Medal::class, $medalAnalytic->medal);
        $this->assertInstanceOf(Season::class, $medalAnalytic->season);
    }
}

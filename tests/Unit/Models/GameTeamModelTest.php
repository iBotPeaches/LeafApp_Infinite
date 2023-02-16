<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\GameTeam;
use Tests\TestCase;

class GameTeamModelTest extends TestCase
{
    /** @dataProvider teamColorDataProvider */
    public function testColorFromTeamName(int $internalId, string $expected): void
    {
        // Arrange
        /** @var GameTeam $gameTeam */
        $gameTeam = GameTeam::factory()
            ->make([
                'internal_team_id' => $internalId,
            ]);

        // Act & Assert
        $this->assertEquals('is-'.$expected, $gameTeam->color);
        $this->assertEquals('has-tooltip-'.$expected, $gameTeam->tooltip_color);
    }

    public function teamColorDataProvider(): array
    {
        return [
            [
                'internal_team_id' => 0,
                'expected' => 'info',
            ],
            [
                'internal_team_id' => 1,
                'expected' => 'danger',
            ],
            [
                'internal_team_id' => 99,
                'expected' => 'dark',
            ],
        ];
    }
}

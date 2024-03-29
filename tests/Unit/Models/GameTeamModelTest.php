<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\GameTeam;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class GameTeamModelTest extends TestCase
{
    #[DataProvider('teamColorDataProvider')]
    public function testColorFromTeamName(int $internalTeamId, string $expected): void
    {
        // Arrange
        /** @var GameTeam $gameTeam */
        $gameTeam = GameTeam::factory()
            ->make([
                'internal_team_id' => $internalTeamId,
            ]);

        // Act & Assert
        $this->assertEquals('is-'.$expected, $gameTeam->color);
        $this->assertEquals('has-tooltip-'.$expected, $gameTeam->tooltip_color);
    }

    public static function teamColorDataProvider(): array
    {
        return [
            [
                'internalTeamId' => 0,
                'expected' => 'info',
            ],
            [
                'internalTeamId' => 1,
                'expected' => 'danger',
            ],
            [
                'internalTeamId' => 99,
                'expected' => 'dark',
            ],
        ];
    }
}

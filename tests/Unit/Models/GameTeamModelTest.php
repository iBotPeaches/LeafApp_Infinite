<?php
declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\GameTeam;
use Tests\TestCase;

class GameTeamModelTest extends TestCase
{
    /** @dataProvider teamColorDataProvider */
    public function testColorFromTeamName(string $name, string $expected): void
    {
        // Arrange
        $gameTeam = GameTeam::factory()
            ->make([
                'name' => $name
            ]);

        // Act & Assert
        $this->assertEquals('is-' . $expected, $gameTeam->color);
        $this->assertEquals('has-tooltip-' . $expected, $gameTeam->tooltip_color);
    }

    public function teamColorDataProvider(): array
    {
        return [
            [
                'name' => 'Eagle',
                'expected' => 'info'
            ],
            [
                'name' => 'Cobra',
                'expected' => 'danger'
            ],
            [
                'name' => 'AAAA',
                'expected' => 'dark'
            ]
        ];
    }
}

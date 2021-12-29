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
        $this->assertEquals($expected, $gameTeam->color);
    }

    public function teamColorDataProvider(): array
    {
        return [
            [
                'name' => 'Eagle',
                'expected' => 'is-info'
            ],
            [
                'name' => 'Cobra',
                'expected' => 'is-danger'
            ],
            [
                'name' => 'AAAA',
                'expected' => 'is-dark'
            ]
        ];
    }
}

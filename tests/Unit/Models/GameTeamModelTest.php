<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\GameTeam;
use App\Services\DotApi\Enums\Team;
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
                'internalTeamId' => Team::EAGLE,
                'expected' => 'eagle',
            ],
            [
                'internalTeamId' => Team::COBRA,
                'expected' => 'cobra',
            ],
            [
                'internalTeamId' => Team::HADES,
                'expected' => 'hades',
            ],
            [
                'internalTeamId' => Team::VALKYRIE,
                'expected' => 'valkyrie',
            ],
            [
                'internalTeamId' => Team::RAMPART,
                'expected' => 'rampart',
            ],
            [
                'internalTeamId' => Team::CUTLASS,
                'expected' => 'cutlass',
            ],
            [
                'internalTeamId' => Team::VALOR,
                'expected' => 'valor',
            ],
            [
                'internalTeamId' => Team::HAZARD,
                'expected' => 'hazard',
            ],
            [
                'internalTeamId' => 99,
                'expected' => 'dark',
            ],
        ];
    }
}

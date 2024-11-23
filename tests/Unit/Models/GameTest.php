<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Enums\Outcome;
use App\Models\Game;
use App\Models\GameTeam;
use Tests\TestCase;

class GameTest extends TestCase
{
    public function test_score_property(): void
    {
        $game = Game::factory()->createOne();
        GameTeam::factory()->createOne([
            'internal_team_id' => 0,
            'game_id' => $game->id,
            'outcome' => Outcome::LOSS,
            'final_score' => 2,
        ]);
        GameTeam::factory()->createOne([
            'internal_team_id' => 1,
            'game_id' => $game->id,
            'outcome' => Outcome::WIN,
            'final_score' => 10,
        ]);
        $this->assertEquals('10-2', $game->score);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\PlayerModePage;

use App\Enums\Outcome;
use App\Livewire\ModePage;
use App\Models\GamePlayer;
use App\Models\Player;
use App\Models\Season;
use App\Support\Session\SeasonSession;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Livewire\Livewire;
use Tests\TestCase;

class ValidModePageTest extends TestCase
{
    public function test_valid_response_from_dot_api(): void
    {
        // Arrange
        $player = Player::factory()->createOne();
        GamePlayer::factory()
            ->count(8)
            ->state(new Sequence(
                ['outcome' => Outcome::WIN],
                ['outcome' => Outcome::LOSS],
                ['outcome' => Outcome::LEFT],
                ['outcome' => Outcome::DRAW],
            ))
            ->create([
                'player_id' => $player->id,
            ]);

        Season::factory()->createOne([
            'key' => config('services.dotapi.competitive.key'),
        ]);

        SeasonSession::set((string) config('services.dotapi.competitive.key'));

        // Act & Assert
        Livewire::test(ModePage::class, [
            'player' => $player,
        ])
            ->assertViewHas('best')
            ->assertViewHas('worse');
    }

    public function test_valid_response_from_dot_api_as_merged_season(): void
    {
        // Arrange
        $player = Player::factory()->createOne();
        GamePlayer::factory()
            ->count(8)
            ->state(new Sequence(
                ['outcome' => Outcome::WIN],
                ['outcome' => Outcome::LOSS],
                ['outcome' => Outcome::LEFT],
                ['outcome' => Outcome::DRAW],
            ))
            ->create([
                'player_id' => $player->id,
            ]);

        SeasonSession::set(SeasonSession::$allSeasonKey);

        // Act & Assert
        Livewire::test(ModePage::class, [
            'player' => $player,
        ])
            ->assertViewHas('best')
            ->assertViewHas('worse');
    }
}

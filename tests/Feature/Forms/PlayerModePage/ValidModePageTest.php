<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\PlayerModePage;

use App\Enums\Outcome;
use App\Livewire\ModePage;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Player;
use App\Models\Season;
use App\Support\Session\SeasonSession;
use Livewire\Livewire;
use Tests\TestCase;

class ValidModePageTest extends TestCase
{
    public function test_valid_response_from_dot_api(): void
    {
        // Arrange
        $player = Player::factory()->createOne();

        // Create multiple games with the same map/category to ensure total > 5 for bestModes/worseModes
        $game = Game::factory()
            ->forPlaylist(['is_ranked' => true])
            ->createOne();

        // Create 6 wins and 6 losses with the same game attributes (map, category)
        // This ensures the grouped query will have totals > 5 for both WIN and LOSS
        GamePlayer::factory()
            ->count(6)
            ->for($game)
            ->create([
                'player_id' => $player->id,
                'outcome' => Outcome::WIN,
            ]);

        GamePlayer::factory()
            ->count(6)
            ->for($game)
            ->create([
                'player_id' => $player->id,
                'outcome' => Outcome::LOSS,
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

        // Create multiple games with the same map/category to ensure total > 5 for bestModes/worseModes
        $game = Game::factory()
            ->forPlaylist(['is_ranked' => true])
            ->createOne();

        // Create 6 wins and 6 losses with the same game attributes (map, category)
        // This ensures the grouped query will have totals > 5 for both WIN and LOSS
        GamePlayer::factory()
            ->count(6)
            ->for($game)
            ->create([
                'player_id' => $player->id,
                'outcome' => Outcome::WIN,
            ]);

        GamePlayer::factory()
            ->count(6)
            ->for($game)
            ->create([
                'player_id' => $player->id,
                'outcome' => Outcome::LOSS,
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

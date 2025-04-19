<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Models\Player;
use App\Models\PlayerBan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\BanSummary\MockBanSummaryService;
use Tests\TestCase;

class CheckForBanTest extends TestCase
{
    use WithFaker;

    public function test_invalid_gamertag(): void
    {
        $this
            ->artisan('app:check-for-ban', ['gamertag' => '999999999'])
            ->assertExitCode(CommandAlias::FAILURE);
    }

    public function test_valid_data_pull_as_banned_user(): void
    {
        // Arrange
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockBannedUser = (new MockBanSummaryService)->banned($gamertag);

        Http::fakeSequence()
            ->push($mockBannedUser, Response::HTTP_OK);

        /** @var Player $player */
        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
        ]);

        // Act & Assert
        $this
            ->artisan('app:check-for-ban', ['gamertag' => $player->gamertag])
            ->expectsOutputToContain('Ban(s) detected!')
            ->assertExitCode(CommandAlias::SUCCESS);

        $this->assertCount(1, $player->bans);

        $this->assertDatabaseHas('players', [
            'id' => $player->id,
            'is_cheater' => true,
        ]);
    }

    public function test_valid_data_pull_as_unbanned_user(): void
    {
        // Arrange
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockBannedUser = (new MockBanSummaryService)->unbanned($gamertag);

        Http::fakeSequence()
            ->push($mockBannedUser, Response::HTTP_OK);

        /** @var Player $player */
        $player = Player::factory()->createOne([
            'gamertag' => $gamertag,
        ]);

        // Act & Assert
        $this
            ->artisan('app:check-for-ban', ['gamertag' => $player->gamertag])
            ->expectsOutputToContain('No bans detected!')
            ->assertExitCode(CommandAlias::SUCCESS);

        $this->assertCount(0, $player->bans);

        $this->assertDatabaseHas('players', [
            'id' => $player->id,
            'is_cheater' => false,
        ]);
    }

    public function test_valid_data_pull_as_unbanned_user_with_historic_ban(): void
    {
        // Arrange
        $gamertag = $this->faker->word.$this->faker->numerify;
        $mockUnbannedUser = (new MockBanSummaryService)->unbanned($gamertag);

        Http::fakeSequence()
            ->push($mockUnbannedUser, Response::HTTP_OK);

        /** @var Player $player */
        $player = Player::factory()
            ->createOne([
                'gamertag' => $gamertag,
            ]);

        PlayerBan::factory()
            ->expired()
            ->create([
                'player_id' => $player->id,
            ]);

        // Act & Assert
        $this
            ->artisan('app:check-for-ban', ['gamertag' => $player->gamertag])
            ->expectsOutputToContain('No bans detected!')
            ->assertExitCode(CommandAlias::SUCCESS);

        $this->assertCount(1, $player->bans);

        $this->assertDatabaseHas('players', [
            'id' => $player->id,
            'is_cheater' => false,
        ]);
    }
}

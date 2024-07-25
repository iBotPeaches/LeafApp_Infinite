<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Models\Player;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\BanSummary\MockBanSummaryService;
use Tests\TestCase;

class CheckForBanTest extends TestCase
{
    use WithFaker;

    public function testInvalidGamertag(): void
    {
        $this
            ->artisan('app:check-for-ban', ['gamertag' => '999999999'])
            ->assertExitCode(CommandAlias::FAILURE);
    }

    public function testValidDataPullAsBannedUser(): void
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

    public function testValidDataPullAsUnbannedUser(): void
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
}

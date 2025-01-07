<?php

declare(strict_types=1);

namespace Feature\Forms\PlayerBanCard;

use App\Livewire\PlayerBanCard;
use App\Models\Player;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\BanSummary\MockBanSummaryService;
use Tests\TestCase;

class ValidPlayerBanCheckTest extends TestCase
{
    public function test_valid_ban_check_loaded(): void
    {
        // Arrange
        $player = Player::factory()->createOne();

        // Act & Assert
        Livewire::test(PlayerBanCard::class, [
            'player' => $player,
        ])
            ->assertSee('Check for ban');
    }

    public function test_valid_ban_check_click_not_logged_in(): void
    {
        // Arrange
        Http::preventStrayRequests();
        $player = Player::factory()->createOne();

        // Act & Assert
        Livewire::test(PlayerBanCard::class, [
            'player' => $player,
        ])
            ->call('banCheck')
            ->assertRedirect(route('login'));
    }

    public function test_valid_ban_check_click_logged_in(): void
    {
        // Arrange
        $user = User::factory()->createOne();
        $player = Player::factory()->createOne();
        $mockBanCheckResponse = (new MockBanSummaryService)->banned($player->gamertag);
        Http::fakeSequence()
            ->push($mockBanCheckResponse, Response::HTTP_OK);

        // Act & Assert
        $this->actingAs($user);
        Livewire::test(PlayerBanCard::class, [
            'player' => $player,
        ])
            ->call('banCheck')
            ->assertRedirect(route('banCheck', $player));
    }
}

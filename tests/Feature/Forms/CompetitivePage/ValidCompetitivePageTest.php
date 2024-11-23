<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\CompetitivePage;

use App\Enums\Input;
use App\Enums\Queue;
use App\Livewire\CompetitivePage;
use App\Models\Csr;
use App\Models\Player;
use App\Models\Playlist;
use App\Support\Session\SeasonSession;
use Carbon\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class ValidCompetitivePageTest extends TestCase
{
    public function test_valid_response_from_dot_api(): void
    {
        // Arrange
        Carbon::setTestNow(now());

        /** @var Playlist $playlist */
        $playlist = Playlist::factory()->createOne();

        $player = Player::factory()
            ->has(Csr::factory()->hasPlaylist($playlist)->state(function () {
                return [
                    'queue' => Queue::OPEN,
                    'input' => Input::CROSSPLAY,
                    'next_csr' => 1500,
                    'tier_start_csr' => 1450,
                    'csr' => 1499,
                ];
            }))
            ->has(Csr::factory()->hasPlaylist($playlist)->state(function () {
                return [
                    'queue' => Queue::SOLO_DUO,
                    'input' => Input::KBM,
                    'next_csr' => 1500,
                    'tier_start_csr' => 1450,
                    'csr' => 1480,
                ];
            }))
            ->has(Csr::factory()->hasPlaylist($playlist)->state(function () {
                return [
                    'queue' => Queue::SOLO_DUO,
                    'input' => Input::CONTROLLER,
                    'next_csr' => 1500,
                    'tier_start_csr' => 1450,
                    'csr' => 1485,
                ];
            }))
            ->createOne();

        /** @var Csr[] $current */
        $current = $player->currentRanked();

        // Act
        $livewire = Livewire::test(CompetitivePage::class, [
            'player' => $player,
        ]);

        // Assert
        $livewire->assertViewHas('current');
        $livewire->assertViewHas('season');
        $livewire->assertViewHas('allTime');

        foreach ($current as $playlist) {
            $livewire->assertSee($playlist->title);
            $livewire->assertSee($playlist->icon, false);
            $livewire->assertSee($playlist->toCsrObject()->url());
            $livewire->assertSee($playlist->rank);
            $livewire->assertSee(number_format($playlist->csr));
        }
    }

    public function test_valid_unranked_response_from_dot_api(): void
    {
        // Arrange
        Carbon::setTestNow(now());
        $player = Player::factory()
            ->has(Csr::factory()->state(function () {
                return [
                    'queue' => Queue::OPEN,
                    'input' => Input::CROSSPLAY,
                    'matches_remaining' => 10,
                    'next_csr' => 0,
                    'tier' => 'Unranked',
                    'tier_start_csr' => 0,
                    'csr' => null,
                ];
            }))
            ->has(Csr::factory()->state(function () {
                return [
                    'queue' => Queue::SOLO_DUO,
                    'input' => Input::CONTROLLER,
                    'next_csr' => 1500,
                    'tier_start_csr' => 1450,
                    'csr' => 1450,
                ];
            }))
            ->createOne();

        /** @var Csr[] $ranked */
        $ranked = $player->currentRanked();

        // Act
        $livewire = Livewire::test(CompetitivePage::class, [
            'player' => $player,
        ]);

        // Assert
        $livewire->assertViewHas('current');
        $livewire->assertViewHas('season');
        $livewire->assertViewHas('allTime');

        foreach ($ranked as $playlist) {
            $livewire->assertSee($playlist->title);
            $livewire->assertSee($playlist->icon, false);
            $livewire->assertSee($playlist->toCsrObject()->url());
            $livewire->assertSee($playlist->rank);
            $livewire->assertSee('In Placements');
        }
    }

    public function test_valid_unranked_response_from_dot_api_in_old_season(): void
    {
        // Arrange
        Carbon::setTestNow(now());
        SeasonSession::set('1-1');

        $player = Player::factory()->createOne();

        /** @var Csr[] $ranked */
        $ranked = $player->currentRanked();

        // Act
        $livewire = Livewire::test(CompetitivePage::class, [
            'player' => $player,
        ]);

        // Assert
        $livewire->assertViewHas('current');
        $livewire->assertViewHas('season');
        $livewire->assertViewHas('allTime');
    }
}

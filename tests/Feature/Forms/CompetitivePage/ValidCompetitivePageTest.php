<?php
declare(strict_types=1);

namespace Tests\Feature\Forms\CompetitivePage;

use App\Enums\Input;
use App\Enums\Queue;
use App\Http\Livewire\CompetitivePage;
use App\Models\Csr;
use App\Models\Player;
use Carbon\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class ValidCompetitivePageTest extends TestCase
{
    public function testValidResponseFromHaloDotApi(): void
    {
        // Arrange
        Carbon::setTestNow(now());
        $player = Player::factory()
            ->has(Csr::factory()->state(function () {
                return ['queue' => Queue::OPEN, 'input' => Input::CROSSPLAY];
            }))
            ->has(Csr::factory()->state(function () {
                return ['queue' => Queue::SOLO_DUO, 'input' => Input::KBM];
            }))
            ->has(Csr::factory()->state(function () {
                return ['queue' => Queue::SOLO_DUO, 'input' => Input::CONTROLLER];
            }))
            ->createOne();

        /** @var Csr[] $ranked */
        $ranked = $player->ranked(1);

        // Act
        $livewire = Livewire::test(CompetitivePage::class, [
            'player' => $player
        ]);

        // Assert
        $livewire->assertViewHas('ranked');

        foreach ($ranked as $playlist) {
            $livewire->assertSee($playlist->title);
            $livewire->assertSee($playlist->icon, false);
            $livewire->assertSee($playlist->tier_image_url);
            $livewire->assertSee($playlist->rank);
            $livewire->assertSee($playlist->csr);
        }
    }
}

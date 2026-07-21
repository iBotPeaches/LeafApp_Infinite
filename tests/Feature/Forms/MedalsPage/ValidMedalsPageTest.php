<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\MedalsPage;

use App\Livewire\MedalsPage;
use App\Models\Player;
use App\Models\ServiceRecord;
use App\Support\Session\SeasonSession;
use Livewire\Livewire;
use Tests\TestCase;

class ValidMedalsPageTest extends TestCase
{
    public function testValidResponseFromDotApi(): void
    {
        // Arrange
        $player = Player::factory()
            ->has(ServiceRecord::factory()->withMedals())
            ->createOne();

        SeasonSession::set((string) config('services.dotapi.competitive.key'));

        // Act & Assert
        Livewire::test(MedalsPage::class, [
            'player' => $player,
        ])
            ->assertViewHas('player')
            ->assertViewHas('medals');
    }

    public function testValidResponseFromDotApiAsMergedSeason(): void
    {
        // Arrange
        $player = Player::factory()
            ->has(ServiceRecord::factory()->withMedals())
            ->createOne();

        SeasonSession::set(SeasonSession::$allSeasonKey);

        // Act & Assert
        Livewire::test(MedalsPage::class, [
            'player' => $player,
        ])
            ->assertViewHas('player')
            ->assertViewHas('medals');
    }
}

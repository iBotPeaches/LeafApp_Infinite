<?php
declare(strict_types=1);

namespace Tests\Feature\Forms\MedalsPage;

use App\Http\Livewire\MedalsPage;
use App\Models\Player;
use App\Models\ServiceRecord;
use Livewire\Livewire;
use Tests\TestCase;

class ValidMedalsPageTest extends TestCase
{
    public function testValidResponseFromHaloDotApi(): void
    {
        // Arrange
        $player = Player::factory()
            ->has(ServiceRecord::factory()->withMedals())
            ->createOne();

        // Act & Assert
        Livewire::test(MedalsPage::class, [
            'player' => $player
        ])
            ->assertViewHas('player')
            ->assertViewHas('medals');
    }
}

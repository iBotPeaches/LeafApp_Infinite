<?php
declare(strict_types=1);

namespace Tests\Feature\Forms\UpdateGamePanel;

use App\Enums\PlayerTab;
use App\Http\Livewire\UpdateGamePanel;
use App\Http\Livewire\UpdatePlayerPanel;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Csrs\MockCsrAllService;
use Tests\Mocks\Matches\MockMatchesService;
use Tests\Mocks\Matches\MockMatchService;
use Tests\Mocks\ServiceRecord\MockServiceRecordService;
use Tests\TestCase;

class InvalidGameUpdateTest extends TestCase
{
    use WithFaker;

    public function testInvalidResponseDueToRateLimit(): void
    {
        // Arrange
        Queue::fake();
        $mockMatchResponse = (new MockMatchService())->error429();

        Http::fakeSequence()
            ->push($mockMatchResponse, Response::HTTP_TOO_MANY_REQUESTS);

        $game = Game::factory()->createOne();

        // Act & Assert
        Livewire::test(UpdateGamePanel::class, [
            'game' => $game,
        ])
            ->call('processUpdate')
            ->assertViewHas('color', 'is-danger')
            ->assertViewHas('message', 'Rate Limit Hit :( - Try later.');
    }

    public function testInvalidResponseDueToCrash(): void
    {
        // Arrange
        Queue::fake();

        Http::fakeSequence()
            ->push([], Response::HTTP_OK);

        $game = Game::factory()->createOne();

        // Act & Assert
        Livewire::test(UpdateGamePanel::class, [
            'game' => $game,
        ])
            ->call('processUpdate')
            ->assertViewHas('color', 'is-danger')
            ->assertViewHas('message', 'Oops - something went wrong.');
    }
}

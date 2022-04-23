<?php
declare(strict_types=1);

namespace Tests\Feature\Forms\UpdateGamePanel;

use App\Http\Livewire\UpdateGamePanel;
use App\Models\Game;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Matches\MockMatchService;
use Tests\TestCase;

class InvalidGameUpdateTest extends TestCase
{
    use WithFaker;

    public function testInvalidResponseDueToGameRateLimit(): void
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

    public function testInvalidResponseDueToUnknownRequestIssue(): void
    {
        // Arrange
        Queue::fake();
        $mockMatchResponse = (new MockMatchService())->error404();

        Http::fakeSequence()
            ->push($mockMatchResponse, Response::HTTP_NOT_FOUND);

        $game = Game::factory()->createOne();

        // Act & Assert
        Livewire::test(UpdateGamePanel::class, [
            'game' => $game,
        ])
            ->call('processUpdate')
            ->assertViewHas('color', 'is-danger')
            ->assertViewHas('message', 'Oops - something went wrong.');
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

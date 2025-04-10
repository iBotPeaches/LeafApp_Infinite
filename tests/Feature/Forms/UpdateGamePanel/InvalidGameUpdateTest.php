<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\UpdateGamePanel;

use App\Livewire\UpdateGamePanel;
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

    public function test_invalid_response_due_to_game_rate_limit(): void
    {
        // Arrange
        Queue::fake();
        $mockMatchResponse = (new MockMatchService)->error429();

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

    public function test_invalid_response_due_to_unknown_request_issue(): void
    {
        // Arrange
        Queue::fake();
        $mockMatchResponse = (new MockMatchService)->error404();

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

    public function test_invalid_response_due_to_crash(): void
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

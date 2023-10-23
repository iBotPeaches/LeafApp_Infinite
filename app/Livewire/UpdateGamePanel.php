<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Game;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Jaybizzle\LaravelCrawlerDetect\Facades\LaravelCrawlerDetect;
use Livewire\Component;

use function Sentry\captureException;

class UpdateGamePanel extends Component
{
    public Game $game;

    public bool $runUpdate = false;

    public function processUpdate(): void
    {
        $this->runUpdate = ! LaravelCrawlerDetect::isCrawler();
    }

    public function render(): View
    {
        $color = 'is-success';
        $message = 'Game updated!';

        if (! $this->runUpdate) {
            return view('livewire.update-game-panel', [
                'color' => 'is-info',
                'message' => 'Checking for missing players.',
            ]);
        }

        try {
            DB::transaction(function () {
                $this->game->lockForUpdate();
                $this->game->updateFromDotApi();
            }, 3);
            $this->emitTo(GamePage::class, '$refresh');
        } catch (RequestException $exception) {
            captureException($exception);
            $color = 'is-danger';
            $message = $exception->getCode() === 429
                ? 'Rate Limit Hit :( - Try later.'
                : 'Oops - something went wrong.';
        } catch (\Throwable $exception) {
            captureException($exception);
            $color = 'is-danger';
            $message = 'Oops - something went wrong.';
        }

        return view('livewire.update-game-panel', [
            'color' => $color,
            'message' => $message,
        ]);
    }
}

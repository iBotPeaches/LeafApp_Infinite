<?php
declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Game;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Component;

class UpdateGamePanel extends Component
{
    public Game $game;
    public bool $runUpdate = false;

    public function processUpdate(): void
    {
        $this->runUpdate = true;
    }

    public function render(): View
    {
        $color = 'is-success';
        $message = 'Game updated!';

        if (!$this->runUpdate) {
            return view('livewire.update-game-panel', [
                'color' => 'is-info',
                'message' => 'Checking for missing players.'
            ]);
        }

        try {
            DB::transaction(function () {
                $this->game->updateFromHaloDotApi();
            });
            $this->emitTo(GamePage::class, '$refresh');
        } catch (RequestException $exception) {
            $color = 'is-danger';
            $message = $exception->getCode() === 429
                ? 'Rate Limit Hit :( - Try later.'
                : 'Oops - something went wrong.';
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            $color = 'is-danger';
            $message = 'Oops - something went wrong.';
        }

        return view('livewire.update-game-panel', [
            'color' => $color,
            'message' => $message
        ]);
    }
}

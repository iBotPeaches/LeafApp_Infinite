<?php
declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Player;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Livewire\Component;

class UpdatePlayerPanel extends Component
{
    public Player $player;
    public bool $runUpdate = false;

    public function processUpdate(): void
    {
        $this->runUpdate = true;
    }

    public function render(): View
    {
        $color = 'is-success';
        $message = 'Profile updated!';

        $cacheKey = 'player-profile-' . $this->player->id;

        if (Cache::has($cacheKey)) {
            $color = 'is-dark';
            $message = 'Profile was recently updated. Check back soon.';
        } else {
            if ($this->runUpdate) {
                $this->player->updateFromHaloDotApi();
                Cache::put($cacheKey, true, 300);
                $this->emitTo(GameHistoryTable::class, '$refresh');
            } else {
                $color = 'is-info';
                $message = 'Checking for updated stats.';
            }
        }

        return view('livewire.update-player-panel', [
            'color' => $color,
            'message' => $message
        ]);
    }
}

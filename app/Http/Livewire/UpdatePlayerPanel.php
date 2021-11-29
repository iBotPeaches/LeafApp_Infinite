<?php
declare(strict_types=1);

namespace App\Http\Livewire;

use App\Enums\PlayerTab;
use App\Models\Player;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Component;

class UpdatePlayerPanel extends Component
{
    public Player $player;
    public string $type;
    public bool $runUpdate = false;

    public function processUpdate(): void
    {
        $this->runUpdate = true;
    }

    public function render(): View
    {
        $color = 'is-success';
        $message = 'Profile updated!';

        $cacheKey = 'player-profile-' . $this->player->id . md5($this->player->gamertag);

        if (Cache::has($cacheKey)) {
            $color = 'is-dark';
            $message = 'Profile was recently updated. Check back soon.';
        } else {
            if ($this->runUpdate) {
                try {
                    DB::transaction(function () use ($cacheKey) {
                        $cooldownMinutes = (int) config('services.halodotapi.cooldown');

                        $this->player->updateFromHaloDotApi();
                        Cache::put($cacheKey, true, now()->addMinutes($cooldownMinutes));
                        $this->emitToRespectiveComponent();
                    });
                } catch (\Throwable $exception) {
                    Log::error($exception->getMessage());
                    $color = 'is-danger';
                    $message = 'Oops - something went wrong.';
                }
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

    private function emitToRespectiveComponent(): void
    {
        switch ($this->type) {
            case PlayerTab::OVERVIEW:
                $this->emitTo(OverviewPage::class, '$refresh');
                break;
            case PlayerTab::COMPETITIVE:
                $this->emitTo(CompetitivePage::class, '$refresh');
                break;
            case PlayerTab::MATCHES:
                $this->emitTo(GameHistoryTable::class, '$refresh');
                break;
        }
    }
}

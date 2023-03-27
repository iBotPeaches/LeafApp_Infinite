<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Enums\PlayerTab;
use App\Models\Player;
use App\Support\Session\SeasonSession;
use Illuminate\Http\Client\RequestException;
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

    // @phpstan-ignore-next-line
    public $listeners = [
        '$refresh',
    ];

    public function processUpdate(): void
    {
        $this->runUpdate = true;
    }

    public function render(): View
    {
        $color = 'is-success';
        $message = 'Profile updated!';

        $seasonNumber = SeasonSession::get();
        $cacheKey = 'player-profile-'.$this->player->id.$seasonNumber.md5($this->player->gamertag);
        $isOlderSeason = $seasonNumber !== -1 && $seasonNumber < (int) config('services.autocode.competitive.season');

        if (Cache::has($cacheKey)) {
            $color = 'is-dark';
            $message = $isOlderSeason
                ? 'Season is old. No more updates will happen.'
                : 'Profile was recently updated (or updating). Check back soon.';
        } else {
            if (! $this->runUpdate) {
                $this->emitToRespectiveComponent();

                return view('livewire.update-player-panel', [
                    'color' => 'is-info',
                    'button' => true,
                    'message' => 'Checking for updated stats.',
                ]);
            }

            try {
                DB::transaction(function () use ($cacheKey, $isOlderSeason) {
                    $cooldownMinutes = (int) config('services.autocode.cooldown');
                    $cooldownUnits = $isOlderSeason ? 'addMonths' : 'addMinutes';
                    Cache::put($cacheKey, true, now()->$cooldownUnits($cooldownMinutes));

                    $this->player->lockForUpdate();
                    $this->player->updateFromHaloDotApi(false, $this->type);
                });
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
        }

        $this->emitToRespectiveComponent();

        return view('livewire.update-player-panel', [
            'color' => $color,
            'message' => $message,
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
            case PlayerTab::CUSTOM:
                $this->emitTo(GameCustomHistoryTable::class, '$refresh');
                break;
            case PlayerTab::LAN:
                $this->emitTo(GameLanHistoryTable::class, '$refresh');
                break;
            case PlayerTab::MODES:
                $this->emitTo(ModePage::class, '$refresh');
                break;
        }
    }
}

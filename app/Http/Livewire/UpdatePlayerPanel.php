<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Enums\PlayerTab;
use App\Models\Player;
use App\Support\Session\SeasonSession;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use function Sentry\captureException;

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

        $season = SeasonSession::model();
        $cacheKey = 'player-profile-'.$this->player->id.$season->key.md5($this->player->gamertag);
        $isOlderSeason = $season->key !== SeasonSession::$allSeasonKey && $season->season_id < (int) config('services.halodotapi.competitive.season');

        if (Cache::has($cacheKey)) {
            $color = 'is-dark';
            $message = $isOlderSeason
                ? 'Season has ended. No more stat updates allowed.'
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
                    $cooldownMinutes = (int) config('services.halodotapi.cooldown');
                    $cooldownUnits = $isOlderSeason ? 'addMonths' : 'addMinutes';
                    Cache::put($cacheKey, true, now()->$cooldownUnits($cooldownMinutes));

                    $this->player->lockForUpdate();
                    $this->player->updateFromHaloDotApi(false, $this->type);
                }, 3);
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

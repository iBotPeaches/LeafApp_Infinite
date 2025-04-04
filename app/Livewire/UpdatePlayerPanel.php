<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\PlayerTab;
use App\Models\Player;
use App\Support\Session\SeasonSession;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Jaybizzle\LaravelCrawlerDetect\Facades\LaravelCrawlerDetect;
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
        $this->runUpdate = ! LaravelCrawlerDetect::isCrawler();
    }

    public function render(): View
    {
        $color = 'is-success';
        $message = 'Profile updated!';

        $season = SeasonSession::model();
        $cacheKey = 'player-profile-'.$this->player->id.$season->key.md5($this->player->gamertag);
        $isOlderSeason = $season->key !== SeasonSession::$allSeasonKey && $season->season_id < (int) config('services.dotapi.competitive.season');

        if (Cache::has($cacheKey)) {
            $color = 'is-dark';

            $message = match (true) {
                $this->player->is_throttled => 'Player is throttled. Updates are delayed.',
                $isOlderSeason => 'Season has ended. No more stat updates allowed.',
                default => 'Profile was recently updated (or updating). Check back soon.',
            };
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
                    $cooldownMinutes = $this->player->is_throttled
                        ? 60 * 24
                        : config()->integer('services.dotapi.cooldown');
                    $cooldownUnits = $isOlderSeason ? 'addMonths' : 'addMinutes';
                    Cache::put($cacheKey, true, now()->$cooldownUnits($cooldownMinutes));

                    $this->player->lockForUpdate();
                    $this->player->updateFromDotApi(false, $this->type);
                    if ($this->player->isDirty()) {
                        $this->player->save();
                    }
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
                $this->dispatch('$refresh')->to(PlayerOverviewPage::class);
                break;
            case PlayerTab::COMPETITIVE:
                $this->dispatch('$refresh')->to(CompetitivePage::class);
                break;
            case PlayerTab::MATCHES:
                $this->dispatch('$refresh')->to(GameHistoryTable::class);
                break;
            case PlayerTab::CUSTOM:
                $this->dispatch('$refresh')->to(GameCustomHistoryTable::class);
                break;
            case PlayerTab::LAN:
                $this->dispatch('$refresh')->to(GameLanHistoryTable::class);
                break;
            case PlayerTab::MODES:
                $this->dispatch('$refresh')->to(ModePage::class);
                break;
        }
        $this->dispatch('$refresh')->to(PlayerCard::class);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Player;
use App\Models\Season;
use App\Support\Session\SeasonSession;
use Illuminate\View\View;
use Livewire\Component;

class CompetitivePage extends Component
{
    public Player $player;

    // @phpstan-ignore-next-line
    public $listeners = [
        '$refresh',
    ];

    public function render(): View
    {
        $latestMmr = $this->player->games()
            ->whereNotNull('mmr')
            ->where('mmr', '!=', 0)
            ->where('games.is_ffa', true)
            ->orderByDesc('games.occurred_at')
            ->first();

        $seasonModel = SeasonSession::model();
        $seasonKey = $seasonModel->key === SeasonSession::$allSeasonKey ? null : $seasonModel->key;
        $isCurrentSeason = $seasonModel->key === config('services.halodotapi.competitive.key');
        $isAllSeasons = $seasonModel->key === SeasonSession::$allSeasonKey;

        // Support for Winter Season, which is a different Season for Service Records, but shared CSR
        $seasonKey = $seasonKey === '2-2' ? '2-1' : $seasonKey;

        if (! $seasonKey) {
            $season = Season::latestOfSeason((int) config('services.halodotapi.competitive.season'));
            $seasonKey = $season?->key;
        }

        return view('livewire.competitive-page', [
            'current' => $this->player->currentRanked($seasonKey, ($isCurrentSeason || $isAllSeasons)),
            'season' => $this->player->seasonHighRanked($seasonKey),
            'allTime' => $this->player->allTimeRanked(),
            'latestMmr' => $latestMmr,
            'isCurrentSeason' => $isCurrentSeason,
            'isAllSeasons' => $isAllSeasons,
        ]);
    }
}

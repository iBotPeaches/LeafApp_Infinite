<?php

declare(strict_types=1);

namespace App\Livewire;

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
        $isCurrentSeason = $seasonModel->key === config('services.dotapi.competitive.key');
        $isAllSeasons = $seasonModel->key === SeasonSession::$allSeasonKey;

        // Support for Winter Season, which is a different Season for Service Records, but shared CSR
        $seasonKey = $seasonKey === '2-2' ? '2-1' : $seasonKey;

        if (! $seasonKey) {
            $season = Season::latestOfSeason((int) config('services.dotapi.competitive.season'));
            $seasonKey = $season?->key;

            // Hack - 5-2 and 5-3 were pre-published, but not yet out.
            // As of now they have the same CSR Key as 5-1, so we'll just use that.
            $seasonKey = ($seasonKey === '5-2' || $seasonKey === '5-3') ? '5-1' : $seasonKey;

            // Hack Part 2 - 6-3 and 6-2 were pre-published, but not yet out.
            // As of now they have the same CSR Key as 6-1, so we'll just use that.
            $seasonKey = ($seasonKey === '6-2' || $seasonKey === '6-3') ? '6-1' : $seasonKey;

            // Hack Part 3 - 7-2 and 7-3 were pre-published, but not yet out.
            // As of now they have the same CSR Key as 7-1, so we'll just use that.
            $seasonKey = ($seasonKey === '7-2' || $seasonKey === '7-3') ? '7-1' : $seasonKey;

            // Hack Part 4 - 8-2 and 8-3 were pre-published, but not yet out.
            // As of now they have the same CSR Key as 8-1, so we'll just use that.
            $seasonKey = ($seasonKey === '8-2' || $seasonKey === '8-3') ? '8-1' : $seasonKey;

            // Hack Part 5 - 9-2 and 9-3 were pre-published, but not yet out.
            // As of now they have the same CSR Key as 9-1, so we'll just use that.
            $seasonKey = ($seasonKey === '9-2' || $seasonKey === '9-3') ? '9-1' : $seasonKey;

            // Hack Part 6 - 10-2 and 10-3 were pre-published, but not yet out.
            // As of now they have the same CSR Key as 10-1, so we'll just use that.
            $seasonKey = ($seasonKey === '10-2' || $seasonKey === '10-3') ? '10-1' : $seasonKey;

            // Hack Part 7 - 11-2 and 11-3 were pre-published, but not yet out.
            // As of now they have the same CSR Key as 11-1, so we'll just use that.
            $seasonKey = ($seasonKey === '11-2' || $seasonKey === '11-3') ? '11-1' : $seasonKey;
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

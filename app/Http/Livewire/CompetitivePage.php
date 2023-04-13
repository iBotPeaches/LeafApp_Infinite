<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Player;
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
            ->where('games.is_ffa', true)
            ->orderByDesc('games.occurred_at')
            ->first();

        $seasonModel = SeasonSession::model();

        return view('livewire.competitive-page', [
            'current' => $this->player->currentRanked($seasonModel),
            'season' => $this->player->seasonHighRanked($seasonModel),
            'allTime' => $this->player->allTimeRanked(),
            'latestMmr' => $latestMmr,
            'isCurrentSeason' => $seasonModel->key === (int) config('services.halotdotapi.competitive.key'),
            'isAllSeasons' => $seasonModel->key === SeasonSession::$allSeasonKey,
        ]);
    }
}

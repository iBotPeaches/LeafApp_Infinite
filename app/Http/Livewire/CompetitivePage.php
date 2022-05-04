<?php
declare(strict_types = 1);

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
        '$refresh'
    ];

    public function render(): View
    {
        $latestMmr = $this->player->games()
            ->whereNotNull('mmr')
            ->where('games.is_ffa', true)
            ->orderByDesc('games.occurred_at')
            ->first();

        $season = SeasonSession::get();

        return view('livewire.competitive-page', [
            'current' => $this->player->currentRanked($season),
            'season' => $this->player->seasonHighRanked($season),
            'allTime' => $this->player->allTimeRanked(),
            'latestMmr' => $latestMmr,
            'isCurrentSeason' => $season === (int)config('services.autocode.competitive.season')
        ]);
    }
}

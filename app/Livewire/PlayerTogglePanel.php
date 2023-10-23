<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Season;
use App\Support\Session\ModeSession;
use App\Support\Session\SeasonSession;
use Illuminate\View\View;
use Livewire\Component;

class PlayerTogglePanel extends Component
{
    public int $playerType;

    public string $type;

    public string $seasonKey;

    public function onChange(): void
    {
        ModeSession::set($this->playerType);
        $this->emitToComponents();
    }

    public function onSeasonChange(): void
    {
        SeasonSession::set($this->seasonKey);
        $this->emitToComponents();
    }

    public function mount(): void
    {
        $this->playerType = (int) ModeSession::get()->value;
        $this->seasonKey = SeasonSession::get();
    }

    public function render(): View
    {
        $seasons = Season::all();

        return view('livewire.player-toggle-panel', [
            'playerType' => $this->playerType,
            'season' => $this->seasonKey,
            'seasons' => $seasons,
        ]);
    }

    private function emitToComponents(): void
    {
        $this->dispatch(OverviewPage::class, '$refresh');
        $this->dispatch(MedalsPage::class, '$refresh');
        $this->dispatch(MedalsLeaderboard::class, '$refresh');
        $this->dispatch(CompetitivePage::class, '$refresh');

        // We will refresh the UpdatePlayerPanel so someone swapping between seasons can immediately get a stat update.
        $this->dispatch(UpdatePlayerPanel::class, '$refresh');
    }
}

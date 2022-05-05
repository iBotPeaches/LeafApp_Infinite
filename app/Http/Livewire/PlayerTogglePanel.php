<?php
declare(strict_types=1);

namespace App\Http\Livewire;

use App\Support\Session\ModeSession;
use App\Support\Session\SeasonSession;
use Illuminate\View\View;
use Livewire\Component;

class PlayerTogglePanel extends Component
{
    public int $playerType;
    public string $type;
    public int $season;

    public function onChange(): void
    {
        ModeSession::set($this->playerType);
        $this->emitToComponents();
    }

    public function onSeasonChange(): void
    {
        SeasonSession::set($this->season);
        $this->emitToComponents();
    }

    public function mount(): void
    {
        $this->playerType = (int)ModeSession::get()->value;
        $this->season = SeasonSession::get();
    }

    public function render(): View
    {
        return view('livewire.player-toggle-panel', [
            'playerType' => $this->playerType,
            'season' => $this->season
        ]);
    }

    private function emitToComponents(): void
    {
        $this->emitTo(OverviewPage::class, '$refresh');
        $this->emitTo(MedalsPage::class, '$refresh');
        $this->emitTo(MedalsLeaderboard::class, '$refresh');
        $this->emitTo(CompetitivePage::class, '$refresh');

        // We will refresh the UpdatePlayerPanel so someone swapping between seasons can immediatly get a stat update.
        $this->emitTo(UpdatePlayerPanel::class, '$refresh');
    }
}

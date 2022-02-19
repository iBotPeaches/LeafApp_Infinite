<?php
declare(strict_types=1);

namespace App\Http\Livewire;

use App\Support\Session\ModeSession;
use Illuminate\View\View;
use Livewire\Component;

class PlayerTogglePanel extends Component
{
    public int $playerType;

    public function onChange(): void
    {
        ModeSession::set($this->playerType);

        $this->emitTo(OverviewPage::class, '$refresh');
        $this->emitTo(MedalsPage::class, '$refresh');
        $this->emitTo(MedalsLeaderboard::class, '$refresh');
    }

    public function mount(): void
    {
        $this->playerType = (int)ModeSession::get()->value;
    }

    public function render(): View
    {
        return view('livewire.player-toggle-panel', [
            'playerType' => $this->playerType
        ]);
    }
}

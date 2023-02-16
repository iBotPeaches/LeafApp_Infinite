<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Player;
use App\Support\Session\ModeSession;
use App\Support\Session\SeasonSession;
use Illuminate\View\View;
use Livewire\Component;

class OverviewPage extends Component
{
    public Player $player;

    // @phpstan-ignore-next-line
    public $listeners = [
        '$refresh',
    ];

    public function render(): View
    {
        $serviceRecordType = ModeSession::get()->toPlayerRelation();
        $season = SeasonSession::get();

        return view('livewire.overview-page', [
            'serviceRecord' => $this->player->$serviceRecordType()->ofSeason($season)->first(),
        ]);
    }
}

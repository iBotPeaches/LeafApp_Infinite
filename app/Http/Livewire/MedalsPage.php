<?php
declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Medal;
use App\Models\Player;
use App\Support\Session\ModeSession;
use App\Support\Session\SeasonSession;
use Illuminate\View\View;
use Livewire\Component;

class MedalsPage extends Component
{
    public Player $player;

    // @phpstan-ignore-next-line
    public $listeners = [
        '$refresh'
    ];

    public function render(): View
    {
        $serviceRecordType = ModeSession::get()->toPlayerRelation();
        $season = SeasonSession::get();

        $medals = Medal::all()->map(function (Medal $medal) use ($serviceRecordType, $season) {
            $medal['count'] = $this->player->$serviceRecordType()->ofSeason($season)->first()->medals[$medal->id] ?? 0;
            return $medal;
        })->sortBy('name');

        return view('livewire.medals-page', [
            'player' => $this->player,
            'medals' => $medals,
        ]);
    }
}

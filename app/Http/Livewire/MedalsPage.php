<?php
declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Medal;
use App\Models\Player;
use App\Support\Session\ModeSession;
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

        $medals = Medal::all()->map(function (Medal $medal) use ($serviceRecordType) {
            $medal['count'] = $this->player->$serviceRecordType->medals[$medal->id] ?? 0;
            return $medal;
        })->sortBy('name');

        return view('livewire.medals-page', [
            'player' => $this->player,
            'medals' => $medals,
        ]);
    }
}

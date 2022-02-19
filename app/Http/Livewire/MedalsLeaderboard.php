<?php
declare(strict_types = 1);

namespace App\Http\Livewire;

use App\Models\Medal;
use App\Models\ServiceRecord;
use App\Support\Session\ModeSession;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class MedalsLeaderboard extends Component
{
    use WithPagination;

    public Medal $medal;

    // @phpstan-ignore-next-line
    public $listeners = [
        '$refresh'
    ];

    public function paginationView(): string
    {
        return 'pagination::bulma';
    }

    public function render(): View
    {
        $modeSession = ModeSession::get();

        $results = ServiceRecord::query()
            ->with('player')
            ->selectRaw('CAST(JSON_EXTRACT(medals, "$.' . $this->medal->id . '") as unsigned) as value,
                mode, total_seconds_played, player_id')
            ->where('mode', $modeSession->value)
            ->whereRaw('CAST(JSON_EXTRACT(medals, "$.' . $this->medal->id . '") as unsigned) > 0')
            ->orderByRaw('value DESC')
            ->paginate(15);

        return view('livewire.medals-leaderboard', [
            'results' => $results
        ]);
    }
}

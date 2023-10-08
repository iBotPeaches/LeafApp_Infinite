<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Medal;
use App\Models\MedalAnalytic;
use App\Support\Session\ModeSession;
use App\Support\Session\SeasonSession;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class MedalsLeaderboard extends Component
{
    use WithPagination;

    public Medal $medal;

    // @phpstan-ignore-next-line
    public $listeners = [
        '$refresh',
    ];

    public function paginationView(): string
    {
        return 'pagination::bulma';
    }

    public function render(): View
    {
        $mode = ModeSession::get();
        $season = SeasonSession::model();

        $query = MedalAnalytic::query()
            ->with('player')
            ->where('medal_id', $this->medal->id)
            ->where('mode', $mode->value)
            ->orderByRaw('value DESC, total_seconds_played DESC');

        if ($season->key === SeasonSession::$allSeasonKey) {
            $query->whereNull('season_id');
        } else {
            $query->where('season_id', $season->id);
        }
        $results = $query->paginate(15);

        return view('livewire.medals-leaderboard', [
            'results' => $results,
            'season' => $season,
            'mode' => $mode,
        ]);
    }
}

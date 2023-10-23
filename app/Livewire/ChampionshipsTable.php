<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Championship;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ChampionshipsTable extends Component
{
    use WithPagination;

    public function paginationView(): string
    {
        return 'pagination::bulma';
    }

    public function render(): View
    {
        $championships = Championship::query()
            ->orderByDesc('started_at')
            ->paginate();

        return view('livewire.championships-table', [
            'championships' => $championships,
        ]);
    }
}

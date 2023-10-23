<?php

namespace App\Livewire;

use App\Models\Analytic;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class TopTenTable extends Component
{
    use WithPagination;

    public function paginationView(): string
    {
        return 'pagination::bulma';
    }

    public function render(): View
    {
        $stats = Analytic::query()
            ->select('key')
            ->orderBy('key')
            ->groupBy('key')
            ->paginate(100);

        return view('livewire.top-ten-table', [
            'stats' => $stats,
        ]);
    }
}

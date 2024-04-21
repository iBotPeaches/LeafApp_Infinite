<?php

namespace App\Livewire;

use App\Models\Overview;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class OverviewsTable extends Component
{
    use WithPagination;

    public function paginationView(): string
    {
        return 'pagination::bulma';
    }

    public function render(): View
    {
        $overviews = Overview::query()
            ->orderBy('name')
            ->paginate(12);

        return view('livewire.overviews-table', [
            'overviews' => $overviews,
        ]);
    }
}

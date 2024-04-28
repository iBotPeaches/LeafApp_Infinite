<?php

namespace App\Livewire;

use App\Enums\OverviewType;
use App\Models\Overview;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class OverviewsTable extends Component
{
    use WithPagination;

    public string $type = OverviewType::MATCHMAKING;

    public function paginationView(): string
    {
        return 'pagination::bulma';
    }

    public function render(): View
    {
        $overviews = Overview::query()
            ->where('is_manual', $this->type === OverviewType::CUSTOMS)
            ->orderBy('name')
            ->paginate(12);

        return view('livewire.overviews-table', [
            'overviews' => $overviews,
        ]);
    }
}

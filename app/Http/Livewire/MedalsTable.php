<?php
declare(strict_types = 1);

namespace App\Http\Livewire;

use App\Models\Medal;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class MedalsTable extends Component
{
    use WithPagination;

    public function paginationView(): string
    {
        return 'pagination::bulma';
    }

    public function render(): View
    {
        $medals = Medal::query()
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.medals-table', [
            'medals' => $medals
        ]);
    }
}

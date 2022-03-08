<?php
declare(strict_types = 1);

namespace App\Http\Livewire;

use App\Models\Scrim;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ScrimsTable extends Component
{
    use WithPagination;

    public function paginationView(): string
    {
        return 'pagination::bulma';
    }

    public function render(): View
    {
        $scrims = Scrim::query()
            ->with('user.player')
            ->orderByDesc('created_at')
            ->paginate();

        return view('livewire.scrims-table', [
            'scrims' => $scrims
        ]);
    }
}

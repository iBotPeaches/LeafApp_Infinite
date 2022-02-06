<?php
declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Player;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class GameCustomHistoryTable extends Component
{
    use WithPagination;

    public Player $player;

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
        return view('livewire.game-custom-history-table', [
            'games' => $this->player
                ->games()
                ->with(['map', 'category'])
                ->whereDoesntHave('playlist')
                ->orderByDesc('occurred_at')
                ->paginate(16)
        ]);
    }
}

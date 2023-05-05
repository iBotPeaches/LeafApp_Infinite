<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Player;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class GameHistoryTable extends Component
{
    use WithPagination;

    public Player $player;

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
        return view('livewire.game-history-table', [
            'games' => $this->player
                ->games()
                ->with(['playlist', 'map', 'category'])
                ->whereNotNull('playlist_id')
                ->orderByDesc('occurred_at')
                ->paginate(16),
        ]);
    }
}

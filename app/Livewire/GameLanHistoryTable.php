<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Livewire\Traits\HasScrimEditor;
use App\Models\Player;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class GameLanHistoryTable extends Component
{
    use HasScrimEditor;
    use WithPagination;

    public Player $player;

    // @phpstan-ignore-next-line
    public $listeners = [
        '$refresh',
        'toggleScrimMode',
    ];

    public function paginationView(): string
    {
        return 'pagination::bulma';
    }

    public function render(): View
    {
        return view('livewire.game-lan-history-table', [
            'isScrimEditor' => $this->isScrimEditor,
            'games' => $this->player
                ->games()
                ->with(['playlist', 'map', 'category'])
                ->where('is_lan', true)
                ->orderByDesc('occurred_at')
                ->paginate(16),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Livewire\Traits\HasScrimEditor;
use App\Models\Player;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class GameCustomHistoryTable extends Component
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
        return view('livewire.game-custom-history-table', [
            'isScrimEditor' => $this->isScrimEditor,
            'games' => $this->player
                ->games()
                ->with(['map', 'category'])
                ->where(function ($query) {
                    $query
                        ->where('is_lan', '=', false)
                        ->orWhereNull('is_lan');
                })
                ->whereNull('playlist_id')
                ->orderByDesc('occurred_at')
                ->paginate(16),
        ]);
    }
}

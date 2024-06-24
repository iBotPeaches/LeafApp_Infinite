<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Livewire\Traits\HasScrimEditor;
use App\Models\Player;
use App\Models\Playlist;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class GameHistoryTable extends Component
{
    use HasScrimEditor;
    use WithPagination;

    public Player $player;

    #[Url]
    public ?string $playlist = '';

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
        $playlist = null;
        if ($this->playlist) {
            $playlist = Playlist::query()
                ->where('uuid', $this->playlist)
                ->first();
        }

        return view('livewire.game-history-table', [
            'isScrimEditor' => $this->isScrimEditor,
            'games' => $this->player
                ->games()
                ->when($playlist, fn ($query) => $query->where('playlist_id', $playlist?->id))
                ->with(['playlist', 'map', 'category'])
                ->whereNotNull('playlist_id')
                ->orderByDesc('occurred_at')
                ->paginate(16),
        ]);
    }
}

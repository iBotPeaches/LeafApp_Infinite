<?php

namespace App\Livewire;

use App\Models\Player;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class BannedPlayerTable extends Component
{
    use WithPagination;

    public function paginationView(): string
    {
        return 'pagination::bulma';
    }

    public function render(): View
    {
        $players = Player::query()
            ->with('latestBan.player')
            ->whereHas('latestBan', fn ($query) => $query->whereDate('ends_at', '>=', now()))
            ->where('is_cheater', true)
            ->orderBy('gamertag')
            ->paginate();

        return view('livewire.banned-player-table', [
            'players' => $players,
        ]);
    }
}

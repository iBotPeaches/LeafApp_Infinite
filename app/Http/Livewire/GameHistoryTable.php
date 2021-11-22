<?php
declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Player;
use Livewire\Component;
use Livewire\WithPagination;

class GameHistoryTable extends Component
{
    use WithPagination;

    public Player $player;

    public function render()
    {
        return view('livewire.game-history-table', [
            'games' => $this->player->games()->paginate(20)
        ]);
    }
}

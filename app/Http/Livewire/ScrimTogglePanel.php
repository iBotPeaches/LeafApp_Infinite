<?php
declare(strict_types=1);

namespace App\Http\Livewire;

use App\Jobs\ProcessScrim;
use App\Models\Scrim;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Redirector;

class ScrimTogglePanel extends Component
{
    public array $gameIds = [];

    // @phpstan-ignore-next-line
    public $listeners = [
        'syncGameIds',
    ];

    public function syncGameIds(array $gameIds = []): void
    {
        $this->gameIds = $gameIds;
    }

    public function createScrim(): bool|Redirector
    {
        /** @var User $user */
        $user = Auth::user();
        if (count($this->gameIds) > 0) {
            $scrim = Scrim::createScrimWithGames($user, $this->gameIds);
            ProcessScrim::dispatch($scrim);

            return redirect()->route('scrim', $scrim);
        }

        $this->emitTo(GameCustomHistoryTable::class, 'toggleScrimMode');

        return true;
    }

    public function render(): View
    {
        return view('livewire.scrim-toggle-panel', [
            'gameCount' => count($this->gameIds)
        ]);
    }
}

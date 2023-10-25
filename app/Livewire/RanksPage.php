<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Rank;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

class RanksPage extends Component
{
    public function render(): View
    {
        /** @var User|null $user */
        $user = auth()->user();

        return view('livewire.ranks-page', [
            'ranks' => Rank::query()->orderBy('threshold')->get(),
            'player' => $user?->player,
        ]);
    }
}

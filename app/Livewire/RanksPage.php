<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Rank;
use Illuminate\View\View;
use Livewire\Component;

class RanksPage extends Component
{
    public function render(): View
    {
        return view('livewire.ranks-page', [
            'ranks' => Rank::query()->orderBy('threshold')->get()
        ]);
    }
}

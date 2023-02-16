<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Scrim;
use Illuminate\View\View;
use Livewire\Component;

class ScrimOverview extends Component
{
    public Scrim $scrim;

    public function render(): View
    {
        return view('livewire.scrim-overview');
    }
}

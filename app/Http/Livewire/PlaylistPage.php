<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use Illuminate\View\View;
use Livewire\Component;

class PlaylistPage extends Component
{
    public function render(): View
    {
        return view('livewire.playlist-page');
    }
}

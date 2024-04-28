<?php

namespace App\Livewire;

use App\Models\Overview;
use Illuminate\View\View;
use Livewire\Component;

class OverviewCard extends Component
{
    public Overview $overview;

    public function render(): View
    {
        $dateMethod = $this->overview->is_manual ? 'addDay' : 'addMonth';

        return view('livewire.overview-card', [
            'overview' => $this->overview,
            'nextDate' => $this->overview->updated_at->$dateMethod(),
        ]);
    }
}

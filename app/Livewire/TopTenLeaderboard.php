<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\AnalyticKey;
use App\Models\Analytic;
use App\Support\Schedule\ScheduleTimer;
use App\Support\Schedule\ScheduleTimerInterface;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class TopTenLeaderboard extends Component
{
    use WithPagination;

    public function paginationView(): string
    {
        return 'pagination::bulma';
    }

    public string $analyticKey;

    public function render(): View
    {
        $topTen = Analytic::query()
            ->with(['player', 'game', 'map'])
            ->where('key', $this->analyticKey)
            ->orderBy('place')
            ->orderByDesc('id')
            ->paginate(10);

        $analyticEnumKey = AnalyticKey::tryFrom($this->analyticKey);

        /** @var ScheduleTimer $timer */
        $timer = resolve(ScheduleTimerInterface::class);

        return view('livewire.top-ten-leaderboard', [
            'results' => $topTen,
            'analyticClass' => Analytic::getStatFromEnum($analyticEnumKey),
            'nextDate' => $timer->topTenRefreshDate,
        ]);
    }
}

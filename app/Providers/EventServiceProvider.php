<?php
declare(strict_types=1);

namespace App\Providers;

use App\Models\GameTeam;
use App\Models\Player;
use App\Observers\GameTeamObserver;
use App\Observers\PlayerObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    public function boot(): void
    {
        GameTeam::observe(GameTeamObserver::class);
        Player::observe(PlayerObserver::class);
    }
}

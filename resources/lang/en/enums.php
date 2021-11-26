<?php

use App\Enums\Experience;
use App\Enums\Input;
use App\Enums\Outcome;
use App\Enums\PlayerTab;
use App\Enums\Queue;

return [
    Experience::class => [
        Experience::BTB => 'BTB',
        Experience::ARENA => 'Arena',
        Experience::PVE_BOTS => 'PVE Bots',
        Experience::CUSTOM => 'Custom',
        Experience::FEATURED => 'Featured',
    ],
    Outcome::class => [
        Outcome::WIN => 'Win',
        Outcome::LOSS => 'Loss',
        Outcome::LEFT => 'Left',
        Outcome::DRAW => 'Draw',
    ],
    PlayerTab::class => [
        PlayerTab::OVERVIEW => 'Overview',
        PlayerTab::COMPETITIVE => 'Competitive',
        PlayerTab::MATCHES => 'Matches'
    ],
    Input::class => [
        Input::CROSSPLAY => 'CrossPlay',
        Input::CONTROLLER => 'Controller',
        Input::KBM => 'KBM',
    ],
    Queue::class => [
        Queue::OPEN => 'Open',
        Queue::SOLO_DUO => 'Solo/Duo',
    ],
];

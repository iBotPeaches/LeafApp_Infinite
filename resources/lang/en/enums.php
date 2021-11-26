<?php

use App\Enums\Experience;
use App\Enums\Outcome;
use App\Enums\PlayerTab;

return [
    Experience::class => [
        Experience::BTB => 'BTB',
        Experience::ARENA => 'Arena',
        Experience::PVE_BOTS => 'PVE Bots',
        Experience::CUSTOM => 'Custom',
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
];

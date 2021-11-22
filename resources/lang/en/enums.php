<?php

use App\Enums\Experience;
use App\Enums\Outcome;

return [
    Experience::class => [
        Experience::BTB => 'BTB',
        Experience::ARENA => 'Arena',
        Experience::PVE_BOTS => 'PVE Bots',
    ],
    Outcome::class => [
        Outcome::WIN => 'Win',
        Outcome::LOSS => 'Loss',
        Outcome::LEFT => 'Left',
        Outcome::DRAW => 'Draw',
    ]
];

<?php

use App\Enums\Experience;
use App\Enums\Outcome;

return [
    Experience::class => [
        Experience::BTB => 'BTB',
        Experience::ARENA => 'Arena'
    ],
    Outcome::class => [
        Outcome::WIN => 'Win',
        Outcome::LOSS => 'Loss',
        Outcome::LEFT => 'Left'
    ]
];

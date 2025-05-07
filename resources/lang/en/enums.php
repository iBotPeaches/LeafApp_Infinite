<?php

use App\Enums\BaseGametype;
use App\Enums\Bracket;
use App\Enums\CompetitiveMode;
use App\Enums\Experience;
use App\Enums\FaceItStatus;
use App\Enums\Input;
use App\Enums\Outcome;
use App\Enums\PlayerTab;
use App\Enums\Queue;
use App\Enums\ScrimTab;
use App\Services\FaceIt\Enums\Region;

return [
    Experience::class => [
        Experience::BTB => 'BTB',
        Experience::ARENA => 'Arena',
        Experience::PVE_BOTS => 'PVE Bots',
        Experience::CUSTOM => 'Custom',
        Experience::FEATURED => 'Featured',
        Experience::UNKNOWN => 'Unknown',
        Experience::PVE => 'PVE',
        Experience::UNTRACKED => 'Untracked',
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
        PlayerTab::MATCHES => 'Matches',
    ],
    ScrimTab::class => [
        ScrimTab::OVERVIEW => 'Overview',
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
    Region::class => [
        Region::NA => 'NA',
        Region::EU => 'EU',
        Region::OCE => 'OCE',
        Region::LATAM => 'LATAM',
    ],
    Bracket::class => [
        Bracket::WINNERS => 'Winners',
        Bracket::LOSERS => 'Losers',
    ],
    CompetitiveMode::class => [
        CompetitiveMode::CURRENT => 'Current',
        CompetitiveMode::SEASON => 'Season',
        CompetitiveMode::ALL_TIME => 'All Time',
    ],
    FaceItStatus::class => [
        FaceItStatus::UNKNOWN => '?',
        FaceItStatus::FINISHED => 'Complete',
        FaceItStatus::STARTED => 'Started',
        FaceItStatus::CANCELLED => 'Cancelled',
        FaceItStatus::MANUAL_RESULT => 'Manual Result',
        FaceItStatus::SCHEDULING => 'Scheduling',
        FaceItStatus::SCHEDULED => 'Scheduled',
        FaceItStatus::PAUSED => 'Paused',
        FaceItStatus::JOIN => 'Join',
        FaceItStatus::CREATED => 'Created',
        FaceItStatus::ADJUSTMENT => 'Adjusted',
    ],
    BaseGametype::class => [
        BaseGametype::ASSAULT => 'Assault',
        BaseGametype::ATTRITION => 'Attrition',
        BaseGametype::CTF => 'CTF',
        BaseGametype::ELIMINATION => 'Elimination',
        BaseGametype::ESCALATION => 'Escalation',
        BaseGametype::EXTRACTION => 'Extraction',
        BaseGametype::FIREFIGHT => 'Firefight',
        BaseGametype::GRIFBALL => 'Grifball',
        BaseGametype::INFECTION => 'Infection',
        BaseGametype::JUGGERNAUT => 'Juggernaut',
        BaseGametype::KOTH => 'KoTH',
        BaseGametype::LAND_GRAB => 'Land Grab',
        BaseGametype::LSS => 'LSS',
        BaseGametype::MINI_GAME => 'Minigame',
        BaseGametype::ODDBALL => 'Oddball',
        BaseGametype::SLAYER => 'Slayer',
        BaseGametype::STOCKPILE => 'Stockpile',
        BaseGametype::STRONGHOLDS => 'Strongholds',
        BaseGametype::TOTAL_CONTROL => 'Total Control',
        BaseGametype::VIP => 'VIP',
    ],
];

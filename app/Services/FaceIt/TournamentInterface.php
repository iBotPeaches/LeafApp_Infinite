<?php
declare(strict_types=1);

namespace App\Services\FaceIt;

use App\Models\Championship;

interface TournamentInterface
{
    public function championship(string $championshipId): ?Championship;
}

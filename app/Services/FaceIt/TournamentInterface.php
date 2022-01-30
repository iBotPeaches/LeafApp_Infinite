<?php
declare(strict_types=1);

namespace App\Services\FaceIt;

use App\Models\Championship;
use App\Models\Matchup;
use Illuminate\Support\Collection;

interface TournamentInterface
{
    public function championship(string $championshipId): ?Championship;
    public function bracket(Championship $championship): Collection;
    public function matchup(Championship $championship, string $matchupId): ?Matchup;
}

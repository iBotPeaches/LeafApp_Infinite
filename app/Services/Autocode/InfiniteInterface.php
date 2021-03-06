<?php
declare(strict_types=1);

namespace App\Services\Autocode;

use App\Models\Csr;
use App\Models\Game;
use App\Models\Player;
use App\Models\ServiceRecord;
use App\Services\Autocode\Enums\Filter;
use App\Services\Autocode\Enums\Mode;
use Illuminate\Database\Eloquent\Collection;

interface InfiniteInterface
{
    public function appearance(string $gamertag): ?Player;
    public function competitive(Player $player, ?int $season = null): ?Csr;
    public function mmr(Player $player): Player;
    public function matches(Player $player, Mode $mode, bool $forceUpdate = false): Collection;
    public function match(string $matchUuid): ?Game;
    public function metadataMedals(): Collection;
    public function metadataMaps(): Collection;
    public function metadataTeams(): Collection;
    public function metadataPlaylists(): Collection;
    public function metadataCategories(): Collection;
    public function serviceRecord(Player $player, int $season = 1): ?ServiceRecord;
}

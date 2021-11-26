<?php
declare(strict_types=1);

namespace App\Services\HaloDotApi;

use App\Models\Csr;
use App\Models\Player;
use Illuminate\Database\Eloquent\Collection;

interface InfiniteInterface
{
    public function appearance(string $gamertag): ?Player;
    public function competitive(Player $player): ?Csr;
    public function matches(Player $player, bool $forceUpdate = false): ?Collection;
}

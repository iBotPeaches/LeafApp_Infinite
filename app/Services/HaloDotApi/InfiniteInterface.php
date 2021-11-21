<?php
declare(strict_types=1);

namespace App\Services\HaloDotApi;

use App\Models\Player;
use Illuminate\Database\Eloquent\Collection;

interface InfiniteInterface
{
    public function appearance(string $gamertag): ?Player;
    public function matches(Player $player): ?Collection;
}

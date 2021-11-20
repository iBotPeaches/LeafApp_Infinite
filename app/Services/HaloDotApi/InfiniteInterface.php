<?php
declare(strict_types=1);

namespace App\Services\HaloDotApi;

use App\Models\Player;

interface InfiniteInterface
{
    public function appearance(string $gamertag): ?Player;
}

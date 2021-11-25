<?php
declare(strict_types=1);

namespace App\Services\XboxApi;

use App\Models\Player;
use Illuminate\Database\Eloquent\Collection;

interface XboxInterface
{
    public function xuid(string $gamertag): ?string;
}

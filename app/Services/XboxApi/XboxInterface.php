<?php

declare(strict_types=1);

namespace App\Services\XboxApi;

interface XboxInterface
{
    public function xuid(string $gamertag): ?string;
}

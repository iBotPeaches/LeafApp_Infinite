<?php

declare(strict_types=1);

namespace App\Models\Contracts;

interface HasHaloDotApiMetadata
{
    public static function fromMetadata(array $payload): ?static;
}

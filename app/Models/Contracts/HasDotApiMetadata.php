<?php

declare(strict_types=1);

namespace App\Models\Contracts;

interface HasDotApiMetadata
{
    public static function fromMetadata(array $payload): ?self;
}

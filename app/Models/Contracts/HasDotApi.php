<?php

declare(strict_types=1);

namespace App\Models\Contracts;

interface HasDotApi
{
    public static function fromDotApi(array $payload): ?self;
}

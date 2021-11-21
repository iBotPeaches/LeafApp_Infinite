<?php
declare(strict_types=1);

namespace App\Models\Contracts;

interface HasHaloDotApi
{
    public static function fromHaloDotApi(array $payload): ?self;
}

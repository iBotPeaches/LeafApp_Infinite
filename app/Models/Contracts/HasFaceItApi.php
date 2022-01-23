<?php
declare(strict_types=1);

namespace App\Models\Contracts;

interface HasFaceItApi
{
    public static function fromFaceItApi(array $payload): ?self;
}

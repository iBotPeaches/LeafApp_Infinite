<?php
declare(strict_types=1);

namespace App\Services\Tinify;

interface ImageInterface
{
    public function optimize(string $url): ?string;
}

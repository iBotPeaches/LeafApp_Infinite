<?php

declare(strict_types=1);

namespace App\Adapters;

interface FileUtilInterface
{
    public function getFileContents(?string $url): string|false;
}

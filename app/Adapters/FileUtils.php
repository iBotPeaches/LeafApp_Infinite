<?php

declare(strict_types=1);

namespace App\Adapters;

class FileUtils implements FileUtilInterface
{
    public function getFileContents(?string $url): string|false
    {
        return file_get_contents((string) $url);
    }
}

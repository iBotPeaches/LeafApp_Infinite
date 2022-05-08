<?php
declare(strict_types = 1);

namespace App\Adapters;

class FileUtils
{
    public static function getFileContents(string $url): string|false
    {
        return file_get_contents($url);
    }
}

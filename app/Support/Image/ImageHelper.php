<?php
declare(strict_types = 1);

namespace App\Support\Image;

use Illuminate\Support\Str;

class ImageHelper
{
    public static function getInternalFilenameFromAutocode(?string $url): ?string
    {
        $parsedUrl = (string)parse_url($url, PHP_URL_QUERY);
        parse_str($parsedUrl, $result);

        $hash = $result['hash'] ?? null;

        $filepath = base64_decode((string)$hash);
        return $filepath ? Str::afterLast($filepath, '/') : null;
    }
}

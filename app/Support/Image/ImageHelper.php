<?php

declare(strict_types=1);

namespace App\Support\Image;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ImageHelper
{
    public static function getInternalFilenameFromAutocode(?string $url): ?string
    {
        $parsedUrl = (string) parse_url((string) $url, PHP_URL_QUERY);
        parse_str($parsedUrl, $result);

        $hash = $result['hash'] ?? null;

        // @phpstan-ignore-next-line
        $payload = base64_decode((string) $hash);
        $data = json_decode((string) $payload, true);
        $filepath = Arr::get($data, 'path');

        return $filepath ? Str::afterLast($filepath, '/') : null;
    }
}

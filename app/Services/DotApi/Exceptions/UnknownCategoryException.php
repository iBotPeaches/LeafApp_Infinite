<?php

declare(strict_types=1);

namespace App\Services\DotApi\Exceptions;

use Exception;
use Illuminate\Support\Arr;

class UnknownCategoryException extends Exception
{
    public static function fromPayload(array $payload): self
    {
        $exception = new self();
        $exception->message = 'Unknown Category encountered: '.Arr::get($payload, 'properties.category_id');

        return $exception;
    }
}

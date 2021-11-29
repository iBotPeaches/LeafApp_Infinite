<?php

namespace App\Rules;

use App\Services\HaloDotApi\InfiniteInterface;
use Illuminate\Contracts\Validation\Rule;

class ValidInfiniteAccount implements Rule
{
    private InfiniteInterface $apiClient;

    public function __construct()
    {
        $this->apiClient = resolve(InfiniteInterface::class);
    }

    public function passes($attribute, $value): bool
    {
        if (is_string($value)) {
            $player = $this->apiClient->appearance($value);

            if ($player) {
                return $player->saveQuietly();
            }
        }

        return false;
    }

    public function message(): string
    {
        return 'This gamertag was not found in Halo Infinite.';
    }
}

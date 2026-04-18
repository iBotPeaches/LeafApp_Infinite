<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncomingFaceItRequest extends FormRequest
{
    public function authorize(): bool
    {
        $secret = config('services.faceit.webhook.secret');
        $header = $this->header('X-Cat-Dog');

        if ($secret === null || $secret === '' || $header === null) {
            return false;
        }

        return hash_equals($secret, $header);
    }

    public function rules(): array
    {
        return [];
    }
}

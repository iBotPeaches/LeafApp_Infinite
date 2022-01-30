<?php
declare(strict_types = 1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncomingFaceItRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->header('X-Cat-Dog') === config('services.faceit.webhook.secret');
    }

    public function rules(): array
    {
        return [];
    }
}

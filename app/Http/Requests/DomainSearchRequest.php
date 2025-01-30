<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DomainSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'domain' => ['required', 'string', 'min:3', 'regex:/^[a-zA-Z0-9-]+$/'],
            'extension' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'domain.regex' => 'The domain name can only contain letters, numbers, and hyphens.',
            'domain.min' => 'The domain name must be at least 3 characters long.',
        ];
    }
}

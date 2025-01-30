<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DomainTransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'domain_name' => 'required|string|max:255',
            'auth_code' => 'required|string',
            'contact_name' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country_code' => 'required|string|size:2',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'period' => 'integer|min:1|max:10',
        ];
    }

    public function messages(): array
    {
        return [
            'domain_name.required' => 'Domain name is required.',
            'domain_name.max' => 'Domain name is too long.',
            'auth_code.required' => 'Auth code is required.',
            'contact_name.required' => 'Contact name is required.',
            'organization.required' => 'Organization is required.',
            'address.required' => 'Address is required.',
            'city.required' => 'City is required.',
            'state.required' => 'State is required.',
            'postal_code.required' => 'Postal code is required.',
            'country_code.required' => 'Country code is required.',
            'phone.required' => 'Phone is required.',
            'email.required' => 'Email is required.',
            'period.required' => 'Period is required.',
            'period.min' => 'Period must be at least 1.',
            'period.max' => 'Period must be at least 10.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DomainRegistrationRequest extends FormRequest
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
            'domain_name' => 'required|string|max:63',
            'extension' => 'required|string',
            'nameservers' => 'required|array|min:2',
            'nameservers.*' => 'required|string|regex:/^(?!-)[A-Za-z0-9-]{1,63}(?<!-)(\.[A-Za-z0-9-]{1,63})*$/',
            'contact_name' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'string|max:20',
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
            'extension.required' => 'Extension is required.',
            'nameservers.required' => 'Nameservers is required.',
            'nameservers.*.required' => 'Nameservers is required.',
            'contact_name.required' => 'Contact name is required.',
            'organization.required' => 'Organization is required.',
            'address.required' => 'Address is required.',
            'city.required' => 'City is required.',
            'state.required' => 'State is required.',
            'postal_code.required' => 'Postal Code is required.',
            'country_code.required' => 'Country Code is required.',
            'phone.required' => 'Phone is required.',
            'email.required' => 'Email is required.',
            'period.required' => 'Period is required.',
            'period.integer' => 'Period must be a number.',
            'period.min' => 'Period must be a positive number.',
            'period.max' => 'Period must be a positive number.',

        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetUsersRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'filters' => 'array',
            'filters.name' => 'string',
            'filters.email' => 'string',
            'filters.phone_number' => 'string',
            'filters.address' => 'string',
            'filters.created_at' => 'string',
            'filters.is_marketing' => 'integer',
            'items_per_page' => 'integer'
        ];
    }
}

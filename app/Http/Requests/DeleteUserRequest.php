<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Http\FormRequest;

class DeleteUserRequest extends FormRequest
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
            'user_uuid' => ['bail', function ($attribute, $value, $fail) {
                $user = app(UserRepository::class)->getUserByUuid($this->uuid);
                if (!$user) {
                    $fail(__('messages.invalid_user_id'));
                }

                if ($user?->is_admin) {
                    $fail(__('messages.admin_cannot_be_deleted'));
                }
            }],
        ];
    }

    /**
     * Prepare for validation
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_uuid' => $this->uuid
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class EditUserRequest extends FormRequest
{
    private ?User $user;
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
            'user_uuid' => 'exists:users,uuid|bail',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => ['required','string', Rule::unique('users')->ignore($this->user?->id)],
            'address' => 'required|string',
            'phone_number' => 'required|string',
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

        $this->user = app(UserRepository::class)->getUserByUuid($this->uuid);
    }

    /**
     * After validation
     */
    public function after(): array {
        return [
            function (Validator $validator) {
                if ($this->user->is_admin) {
                    $validator->errors()->add('user_uuid', __('messages.admin_cannot_be_edited'));
                }
            }
        ];
    }
}

<?php

namespace App\Http\Requests\Api\Users;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'profile_img' => ['nullable', 'bail', 'image'],
            'first_name' => ['nullable', 'bail', 'string', 'max:255'],
            'last_name' => ['nullable', 'bail', 'string', 'max:255'],
            'email' => ['nullable', 'bail', 'string', 'email', 'unique:users,email', 'max:255'],
            'password' => ['nullable', 'bail', 'confirmed', 'string', 'max:255'],
            'position' => ['nullable', 'bail', 'string', 'max:255'],
            'college' => ['nullable', 'bail', 'string', 'max:255'],
            'contact' => ['nullable', 'bail', 'string', 'max:255'],
            'type' => ['nullable', 'bail', 'string', Rule::in(array_keys(User::TYPES)), 'max:255']
        ];
    }
}

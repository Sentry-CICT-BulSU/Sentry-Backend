<?php

namespace App\Http\Requests\Api\Users;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // dd(Auth::user());
        // return Auth::user()->type === User::TYPES['admin'];
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
            'profile_img' => ['nullable', 'bail', 'image', 'mimes:png,jpg,jpeg,gif'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'unique:users,email', 'max:255'],
            'password' => ['required', 'confirmed', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'college' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', Rule::in(array_keys(User::TYPES)), 'max:255']
        ];
    }
}

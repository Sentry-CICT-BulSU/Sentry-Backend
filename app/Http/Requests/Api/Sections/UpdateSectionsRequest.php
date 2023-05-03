<?php

namespace App\Http\Requests\Api\Sections;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSectionsRequest extends FormRequest
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
            'name' => ['nullable', 'bail', 'string', 'max:255'],
            'status' => ['nullable', 'bail', 'string', 'max:255'],
            'adviser_id' => ['nullable', 'bail', 'exists:users,id'],
        ];
    }
}

<?php

namespace App\Http\Requests\Api\Semesters;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSemestersRequest extends FormRequest
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
            'academic_year' => ['nullable', 'bail', 'string', 'max:255'],
            'duration' => ['nullable', 'bail', 'array', 'max:255'],
            'duration.*' => ['required_with:duration', 'string', 'max:255'],
            'status' => ['nullable', 'bail', 'string', 'max:255'],
        ];
    }
}

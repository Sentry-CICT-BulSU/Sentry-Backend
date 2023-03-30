<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSectionsRequest extends FormRequest
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
            'semester_id' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'faculty_adviser' => ['required', 'exists:users,id'],
        ];
    }
}

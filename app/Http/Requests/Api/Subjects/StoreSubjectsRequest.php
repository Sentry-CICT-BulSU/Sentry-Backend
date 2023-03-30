<?php

namespace App\Http\Requests\Api\Subjects;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectsRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255', 'unique:subjects,title'],
            'code' => ['required', 'string', 'max:255', 'unique:subjects,code'],
            'status' => ['required', 'string', 'max:255'],
            'section_id' => ['required', 'string', 'max:255', 'exists:sections,id'],
        ];
    }
}

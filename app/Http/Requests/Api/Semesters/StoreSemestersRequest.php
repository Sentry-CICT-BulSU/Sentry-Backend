<?php

namespace App\Http\Requests\Api\Semesters;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSemestersRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'academic_year' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'duration' => ['required', 'array', 'min:2', 'max:2'],
            'duration.*' => ['required', 'string', 'max:255'],
        ];
    }
}

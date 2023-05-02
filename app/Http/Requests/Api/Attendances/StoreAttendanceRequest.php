<?php

namespace App\Http\Requests\Api\Attendances;

use App\Models\Attendances;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendanceRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'status' => ['required', 'string', 'max:255', Rule::in(Attendances::STATUSES)],
            'remarks' => ['nullable', 'bail', 'string', 'max:255'],
            'attachment' => ['nullable', 'bail', 'file'],
        ];
    }
}

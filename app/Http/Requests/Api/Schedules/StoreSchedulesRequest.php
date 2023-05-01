<?php

namespace App\Http\Requests\Api\Schedules;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchedulesRequest extends FormRequest
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
            'adviser_id' => ['required', 'string', 'max:255', 'exists:users,id'],
            'subject_id' => ['required', 'string', 'max:255', 'exists:subjects,id'],
            'semester_id' => ['required', 'string', 'max:255', 'exists:semesters,id'],
            'room_id' => ['required', 'string', 'max:255', 'exists:rooms,id'],
            'section_id' => ['required', 'string', 'max:255', 'exists:sections,id'],
            // 'date_start' => ['required', 'string', 'max:255'],
            // 'date_end' => ['required', 'string', 'max:255'],
            'time_start' => ['required', 'string', 'max:255'],
            'time_end' => ['required', 'string', 'max:255'],
            'active_days' => ['required', 'array', 'max:7', 'min:1'],
            'active_days.*' => ['required', 'string', 'max:255', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
        ];
    }
}

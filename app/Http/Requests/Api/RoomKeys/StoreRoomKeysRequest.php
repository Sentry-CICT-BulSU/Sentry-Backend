<?php

namespace App\Http\Requests\Api\RoomKeys;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomKeysRequest extends FormRequest
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
            'room_key_id' => ['required', 'exists:room_keys,id'],
            'faculty_id' => ['nullable', 'bail', 'exists:users,id'],
            'subject_id' => ['nullable', 'bail', 'exists:subjects,id'],
            'time_block' => ['required', 'string'],
            'is_null' => ['nullable', 'bail', 'boolean'],
        ];
    }
}

<?php

namespace App\Http\Requests\Tutor;

use Illuminate\Foundation\Http\FormRequest;

class EditWorkDaysRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'monday' => 'nullable|boolean',
            'tuesday' => 'nullable|boolean',
            'wednesday' => 'nullable|boolean',
            'thursday' => 'nullable|boolean',
            'friday' => 'nullable|boolean',
            'saturday' => 'nullable|boolean',
            'sunday' => 'nullable|boolean',
            'start_time' => 'nullable|date_format:H:i:s',
            'end_time' => 'nullable|date_format:H:i:s',
        ];
    }
}

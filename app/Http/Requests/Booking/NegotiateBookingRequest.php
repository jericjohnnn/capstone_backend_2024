<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class NegotiateBookingRequest extends FormRequest
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
            'message_title' => 'nullable|string|max:255',
            'message_content' => 'nullable|string',
            'selected_date_times' => 'nullable|array|min:1',
            'selected_date_times.*.start' => 'nullable|date_format:Y-m-d H:i:s|before:selected_date_times.*.end',
            'selected_date_times.*.end' => 'nullable|date_format:Y-m-d H:i:s|after:selected_date_times.*.start',
        ];
    }
}

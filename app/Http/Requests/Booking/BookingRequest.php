<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
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
            'tutor_id' => 'required|integer|exists:tutors,id',
            'student_id' => 'required|integer',
            'subject' => 'required|string|max:255',
            'learning_mode' => 'required|string|in:Face to Face,In School,Online',
            'location' => 'nullable|string|max:255',
            'online_meeting_platform' => 'nullable|string|max:255',
            'contact_number' => 'required|string|max:15',
            'message_title' => 'required|string|max:255',
            'message_content' => 'required|string|max:1000',
            'selected_date_times' => 'required|array|min:1',
            'selected_date_times.*.start' => 'required|date_format:Y-m-d H:i:s|before:selected_date_times.*.end',
            'selected_date_times.*.end' => 'required|date_format:Y-m-d H:i:s|after:selected_date_times.*.start',
        ];
    }
}

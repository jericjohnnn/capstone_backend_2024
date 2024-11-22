<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'user_type_id' => 'required|exists:user_types,id', //required
            'email' => 'required|string|email|max:255|unique:users,email', //required
            'password' => 'required|string|min:8|confirmed', //required
            'first_name' => 'required|string|max:255', //required
            'last_name' => 'required|string|max:255', //required
            'address' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'gender' => 'in:male,female,other',
            'contact_number' => 'required|string',
            'grade_year' => 'string|max:255',
            'subjects' => 'nullable|array',
            'subjects.*' => 'integer',
            'school_id_number' => 'string|max:255',
            'course' => 'in:BSED,BEED,BSIT,BSHM,BSENTREP',
            'year' => 'in:1,2,3,4',
        ];
    }
}

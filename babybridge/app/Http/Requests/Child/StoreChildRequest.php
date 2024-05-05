<?php

namespace App\Http\Requests\Child;

use Illuminate\Foundation\Http\FormRequest;

class StoreChildRequest extends FormRequest
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
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'section' => 'required|exists:sections,id',
            'special_infos' => 'nullable|string',
            'tutor_lastname.*' => 'required|string|max:60',
            'tutor_firstname.*' => 'required|string|max:60',
            'tutor_email.*' => 'required|email',
            'tutor_phone.*' => 'sometimes|string|max:20',
            'tutor_language.*' => 'sometimes|string|max:2',
            'tutor_address.*' => 'sometimes|string|max:255',
            'tutor_postal_code.*' => 'sometimes|string|max:6',
            'tutor_city.*' => 'sometimes|string|max:50',
        ];
    }
}

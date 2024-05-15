<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            // 

            'title' => 'required|string|max:100',
            'schedule' => 'nullable|date',
            'description' => 'nullable|string',
            'sections' => 'required|array',
            'sections.*' => 'required|exists:sections,id',
            'price' => 'nullable|numeric',
        ];
    }
}

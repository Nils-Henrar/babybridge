<?php

namespace App\Http\Requests\Section;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Section;

class StoreSectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        if ($this->user()->can('create', Section::class)) {
            return true;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:30',
            'type' => 'required|exists:types,id',
        ];
    }
}

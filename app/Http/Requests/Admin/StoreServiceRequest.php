<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:services,name',
            'description' => 'nullable| string',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name' => 'votre nom est obligatoire',
            'description' => 'vous devez saisir une description de ce service',
        ];
    }
}

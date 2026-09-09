<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class PasswordUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
   public function rules(): array
    {
        return [
            'current_password' => [
                'required',
                'current_password',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' =>
                'Votre mot de passe actuel est obligatoire.',

            'current_password.current_password' =>
                'Votre mot de passe actuel est incorrect.',

            'password.required' =>
                'Le nouveau mot de passe est obligatoire.',

            'password.confirmed' =>
                'La confirmation du mot de passe ne correspond pas.',
        ];
    }
}


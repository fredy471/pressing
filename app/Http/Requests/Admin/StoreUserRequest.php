<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone' => 'required|string|size:10|unique:users,phone|regex:/[0-9]+$/',
            'email' => 'required|string|unique:users,email',
            'domicile' => ['required_if:role,agent', 'nullable', 'string', 'max:500',],
            'role' => 'required|Rule:in:agent,client',
            
        ];
    }

    public function messages(): array
    {
        return [
            'name' => 'le nom de l\'utilisateur est requis',
            'phone' => 'numero de telephone obligatoire',
            'email' => 'email obligatoire',
            'domicile' => 'vous devez renseignez votre domicile',
            'password' => 'mot de passe obligatoire',
            'role' => 'role obligatoire',
        ];
    }

    #[Override]
    public function prepareForValidation()
    {
        $this->merge([
            'email' => strtolower($this->email),
        ]);
    }
}

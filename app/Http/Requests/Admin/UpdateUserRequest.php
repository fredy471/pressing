<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
            'phone' => 'required|string|size:10|unique:users,phone,' . $this->user->id . '|regex:/[0-9]+$/',
            'email' => 'required|string|unique:users,email,' . $this->user->id,
            'created_by' => 'required|string|exists:users,id',
            'password' => 'nullable|string|min:5|confirmed',
            'role' => 'required|Rule:in:client, agent',
        ];
    }
}

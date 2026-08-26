<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePricesRequest extends FormRequest
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
            'prices' => 'required|array',
            'prices.*.service_id' => 'required|exists:services,id',
            'prices.*.clothes_type_id' => 'required|exists:clothes_types,id',
            'prices.*.price' => 'required|numeric|min:0',
        ];
    }
}

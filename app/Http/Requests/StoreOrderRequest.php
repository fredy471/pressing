<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // la route est publique : le visiteur peut préparer sa commande
    }

    public function rules(): array
    {
        return [
            'delivery_mode'   => ['required', Rule::in(['collecte_livraison', 'depot_physique'])],
            'pickup_address'  => ['required_if:delivery_mode,collecte_livraison', 'nullable', 'string', 'max:255'],
            'pickup_phone'    => ['required_if:delivery_mode,collecte_livraison', 'nullable', 'string', 'max:10'],
            'items'                   => ['required', 'array', 'min:1'],
            'items.*.service_id'      => ['required', Rule::exists('services', 'id')->where('is_active', true)],
            'items.*.clothes_type_id' => ['required', Rule::exists('clothes_types', 'id')->where('is_active', true)],
            'items.*.quantity'        => ['required', 'integer', 'min:1'],
        ];
    }
}

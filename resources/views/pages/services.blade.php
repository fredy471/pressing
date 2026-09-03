@extends('layouts.guest')
@section('title', 'Service&tarifs')
@section('content')
    <div class="py-12 max-w-7xl mx-auto px-4" x-data="orderForm()">
        <h1 class="text-3xl font-bold mb-8">Nos Services & Tarifs</h1>

        <!-- 1. Affichage statique des services et prix -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            @foreach($services as $service)
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                <h3 class="text-lg font-semibold text-slate-800">{{ $service->name }}</h3>
                <p class="text-sm text-slate-500 mb-4">{{ $service->description }}</p>
                <span class="text-xl font-bold text-blue-600">{{ number_format($service->price, 2, ',', ' ') }} F / pèce</span>
            </div>
            @endforeach
        </div>

        <!-- 2. Formulaire dynamique de commande -->
        <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-100">
            <h2 class="text-xl font-bold mb-4">Simuler / Composer ma commande</h2>

            <div class="space-y-4">
                <!-- Choix du service -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Sélectionner un service</label>
                    <select x-model="selectedServiceId" @change="updatePrice" class="w-full rounded-lg border-slate-300">
                        <option value="">-- Choisissez un service --</option>
                        @foreach($services as $service)
                        <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                            {{ $service->name }} ({{ number_format($service->price, 2, ',', ' ') }} F/pièce)
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Champ quantité (s'affiche si un service est choisi) -->
                <div x-show="selectedServiceId">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nombre de pièces</label>
                    <input type="number" x-model.number="quantity" min="1" class="w-full rounded-lg border-slate-300">
                </div>

                <!-- Affichage du sous-total dynamique -->
                <div x-show="selectedServiceId" class="text-lg font-semibold text-slate-800 pt-4 border-t">
                    Total estimé : <span class="text-blue-600" x-text="totalPrice + ' FCFA'"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Alpine.js pour la logique dynamique -->
    <script>
        function orderForm() {
            return {
                selectedServiceId: '',
                unitPrice: 0,
                quantity: 1,
                updatePrice(event) {
                    let selectedOption = event.target.options[event.target.selectedIndex];
                    this.unitPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                },
                get totalPrice() {
                    return this.unitPrice * this.quantity;
                }
            }
        }
    </script>
@endsection

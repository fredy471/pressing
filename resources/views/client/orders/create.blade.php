@extends('layouts.guest')
@section('title', 'Commander')
@section('content')

<div class="max-w-2xl mx-auto px-6 py-16">
    <div class="text-center mb-10">
        <h1 class="font-display font-bold text-3xl text-text">Lancer une commande</h1>
        <p class="text-muted text-sm mt-2">Choisissez un service, ajustez vos quantités.</p>
    </div>

    <form method="POST" action="{{ route('orders.store') }}"
        x-data="orderForm({{ Js::from($services) }})"
        @submit="beforeSubmit">
        @csrf

        {{-- Sélecteur de service — tabs --}}
        <div class="flex flex-wrap gap-2 justify-center mb-6">
            @foreach ($services as $service)
            <button type="button" @click="selectService({{ $service->id }})"
                :class="serviceId === {{ $service->id }}
                            ? 'bg-accent text-inverse border-accent'
                            : 'bg-content text-muted border-muted/20 hover:border-accent/40'"
                class="px-4 py-2 rounded-full text-sm font-medium border transition">
                {{ $service->name }}
            </button>
            @endforeach
            <input type="hidden" name="service_id" :value="serviceId">
        </div>

        {{-- Carte principale --}}
        <div class="bg-content rounded-2xl border border-muted/10 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-5 border-b border-muted/10">
                <h2 class="font-display font-semibold text-lg text-text">Estimez votre commande</h2>
                <span x-show="activeServiceName" x-cloak
                    class="text-xs font-semibold text-accent2 bg-accent2/10 px-3 py-1.5 rounded-full"
                    x-text="activeServiceName"></span>
            </div>

            <div x-show="!serviceId" class="px-6 py-10 text-center text-muted text-sm">
                Sélectionnez un service ci-dessus pour voir les tarifs.
            </div>

            {{-- Message de chargement avec transition de fondu subtile --}}
            <div x-show="loading" x-cloak 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-180"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="px-6 py-10 text-center text-text text-sm">
                Chargement des tarifs...
            </div>

           
            <div x-show="serviceId && !loading" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <div x-show="clothesTypes.length === 0" x-cloak class="px-6 py-10 text-center text-muted text-sm">
                    Aucun article n'est encore configuré pour ce service.
                </div>

                <div class="divide-y divide-muted/10">
                    <template x-for="type in clothesTypes" :key="type.id">
                        <div class="flex items-center justify-between px-6 py-4">
                            <div>
                                <p class="text-sm font-semibold text-text" x-text="type.name"></p>
                                <p class="text-xs text-muted font-mono mt-0.5" x-text="formatPrice(prices[type.id]) + ' FCFA / unité'"></p>
                            </div>
                            <input type="number" min="0" inputmode="numeric"
                                x-model.number="quantities[type.id]"
                                @input="if (quantities[type.id] < 0) quantities[type.id] = 0"
                                class="w-24 text-center rounded-lg border-muted/20 bg-bg text-sm font-mono focus:border-accent focus:ring-accent">
                        </div>
                    </template>
                </div>

                {{-- Mode de livraison, intégré discrètement dans la carte --}}
                <div class="px-6 py-4 border-t border-muted/10 flex items-center gap-4">
                    <span class="text-xs font-medium text-muted uppercase tracking-wide">Récupération</span>
                    <label class="flex items-center gap-1.5 text-sm text-text cursor-pointer">
                        <input type="radio" name="delivery_mode" value="depot_physique" x-model="deliveryMode" class="text-accent focus:ring-accent">
                        Dépôt
                    </label>
                    <label class="flex items-center gap-1.5 text-sm text-text cursor-pointer">
                        <input type="radio" name="delivery_mode" value="collecte_livraison" x-model="deliveryMode" class="text-accent focus:ring-accent">
                        Collecte à domicile
                    </label>
                </div>

                <div x-show="deliveryMode === 'collecte_livraison'" x-cloak class="px-6 py-4 border-t border-muted/10">
                    <div class="py-2">
                        <label class="block text-xs font-medium text-muted uppercase tracking-wide mb-1.5">Adresse de collecte</label>
                        <input type="text" name="pickup_address" x-model="pickupAddress"
                            placeholder="Quartier, rue, point de repère..."
                            class="w-full rounded-lg border-muted/20 bg-bg text-sm focus:border-accent focus:ring-accent">
                    </div>

                    <div class="py-2">
                        <label class="block text-xs font-medium text-muted uppercase tracking-wide mb-1.5">Numéro de téléphone</label>
                        <input type="text" name="pickup_phone" x-model="pickupPhone"
                            value="{{ old('pickup_phone', session('phone')) }}"
                            class="w-full rounded-lg border-muted/20 bg-bg text-sm focus:border-accent focus:ring-accent">
                    </div>
                </div>

                <div class="flex items-center justify-between px-6 py-4 bg-inverse/40 border-t border-muted/10">
                    <span class="text-sm text-muted">Total estimé</span>
                    <span class="font-display font-bold text-xl text-text">
                        <span x-text="formatPrice(totalPrice)"></span> FCFA
                    </span>
                </div>
            </div>
        </div>

        {{-- Champs cachés pour la soumission --}}
        <template x-for="(qty, typeId) in quantities" :key="typeId">
            <template x-if="qty > 0">
                <div>
                    <input type="hidden" :name="'items[' + typeId + '][clothes_type_id]'" :value="typeId">
                    <input type="hidden" :name="'items[' + typeId + '][quantity]'" :value="qty">
                </div>
            </template>
        </template>

        <button type="submit"
            :disabled="!canSubmit"
            :class="canSubmit ? 'bg-accent hover:opacity-90' : 'bg-muted/30 cursor-not-allowed text-transparent'"
            class="w-full mt-6 text-inverse font-medium text-sm py-3.5 rounded-full transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-basket-shopping"></i>
            Commander ce pressing
        </button>

        <p class="text-center text-xs text-muted mt-4">
            La connexion vous sera demandée à l'étape suivante pour finaliser votre commande.
        </p>
    </form>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('orderForm', (services) => ({
            services: services,
            serviceId: null,
            deliveryMode: null,
            clothesTypes: [],
            prices: {},
            quantities: {},
            loading: false,

            get activeServiceName() {
                const service = this.services.find(s => s.id === this.serviceId);
                return service ? service.name : '';
            },

            async selectService(id) {
                this.serviceId = id;
                await this.loadClothesTypes();
            },

            async loadClothesTypes() {
                this.loading = true;
                this.clothesTypes = [];
                this.quantities = {};

                const response = await fetch(`/commander/${this.serviceId}/vetements`);
                const data = await response.json();

                this.clothesTypes = data.clothes_types;
                this.prices = data.prices;

                this.clothesTypes.forEach(type => {
                    this.quantities[type.id] = 0;
                });

                this.loading = false;
            },

            formatPrice(value) {
                return new Intl.NumberFormat('fr-FR').format(value || 0);
            },

            get totalPrice() {
                return Object.entries(this.quantities).reduce((sum, [typeId, qty]) => {
                    return sum + (this.prices[typeId] || 0) * qty;
                }, 0);
            },

            get totalItems() {
                return Object.values(this.quantities).reduce((sum, qty) => sum + qty, 0);
            },

            get canSubmit() {
                return this.serviceId && this.deliveryMode && this.totalItems > 0;
            },

            beforeSubmit(event) {
                if (!this.canSubmit) {
                    event.preventDefault();
                }
            },
        }));
    });
</script>
@endpush
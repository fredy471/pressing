@extends('layouts.app')
@section('title', 'Commander')
@section('content')

<div class="w-full">
    <div class="relative isolate overflow-hidden bg-inverse text-white shadow-sm">
        <img src="{{ asset('images/lavage-repassage.jpg') }}" alt="Lavage et repassage"
            class="absolute inset-0 -z-20 h-full w-full object-cover">
        <div class="absolute inset-0 -z-10 bg-black/60"></div>

        <div class="mx-auto max-w-5xl px-6 py-8 sm:px-8 sm:py-10">
            <nav aria-label="Fil d'Ariane" class="mb-6 flex items-center gap-2 text-xs text-white/75">
                <a href="{{ route('home') }}" class="transition-colors hover:text-white">Accueil</a>
                <i class="fa-solid fa-chevron-right text-[10px]" aria-hidden="true"></i>
                <span class="font-medium text-white" aria-current="page">Commander</span>
            </nav>

            <div>
                <p class="mb-2 text-xs font-semibold uppercase tracking-[0.18em] text-white/70">Pressing sur mesure</p>
                <h1 class="font-display text-3xl font-bold text-white sm:text-4xl">Lancer une commande</h1>
                <p class="mt-2 max-w-xl text-sm text-white/80">Sélectionnez vos prestations et composez votre panier en toute simplicité.</p>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-5xl px-6 py-12">
        <form method="POST" action="{{ route('orders.store') }}"
        x-data="orderForm({{ Js::from($services) }})"
        @submit="beforeSubmit">
        @csrf

        <!-- Grille principale en 2 colonnes (sur grand écran) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Colonne de gauche : Services & Articles (8 cols) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Étape 1 : Choix du service -->
                <div class="bg-content rounded-2xl border border-muted/10 p-6 shadow-sm">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-6 h-6 rounded-full bg-accent/10 text-accent text-xs font-bold flex items-center justify-center">1</span>
                        <h2 class="font-semibold text-base text-text">Choisissez votre service</h2>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach ($services as $service)
                        <button type="button" @click="selectService({{ $service->id }})"
                            :class="serviceId === {{ $service->id }}
                                        ? 'bg-accent text-inverse border-accent shadow-sm'
                                        : 'bg-bg text-muted border-muted/20 hover:border-accent/40 hover:text-text'"
                            class="p-4 rounded-xl text-left border transition-all duration-200 flex flex-col justify-between">
                            <span class="text-xs uppercase tracking-wider opacity-80 font-mono">Service</span>
                            <span class="text-sm font-semibold mt-2">{{ $service->name }}</span>
                        </button>
                        @endforeach
                    </div>
                    <input type="hidden" name="service_id" :value="serviceId">
                </div>

                <!-- Étape 2 : Articles et Tarifs (S'affiche si service sélectionné) -->
                <div class="bg-content rounded-2xl border border-muted/10 overflow-hidden shadow-sm" x-show="serviceId" x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    
                    <div class="px-6 py-4 border-b border-muted/10 bg-inverse/[0.01] flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-accent/10 text-accent text-xs font-bold flex items-center justify-center">2</span>
                            <h2 class="font-semibold text-base text-text">Ajustez les quantités</h2>
                        </div>
                        <span class="text-xs font-semibold text-accent bg-accent/10 px-3 py-1 rounded-full" x-text="activeServiceName"></span>
                    </div>

                    <!-- Chargement -->
                    <div x-show="loading" x-cloak class="px-6 py-12 text-center text-text text-sm flex flex-col items-center gap-2">
                        <i class="fa-solid fa-spinner animate-spin text-accent text-lg"></i>
                        <span class="text-muted">Récupération des tarifs...</span>
                    </div>

                    <div x-show="!loading" x-cloak>
                        <div x-show="clothesTypes.length === 0" x-cloak class="px-6 py-10 text-center text-muted text-sm">
                            Aucun article disponible pour ce service.
                        </div>

                        <div class="divide-y divide-muted/10">
                            <template x-for="type in clothesTypes" :key="type.id">
                                <div class="flex items-center justify-between px-6 py-4 hover:bg-inverse/[0.01] transition-colors">
                                    <div>
                                        <p class="text-sm font-semibold text-text" x-text="type.name"></p>
                                        <p class="text-xs text-muted mt-0.5" x-text="formatPrice(prices[type.id]) + ' FCFA / unité'"></p>
                                    </div>
                                    <input type="number" min="0" inputmode="numeric"
                                        x-model.number="quantities[type.id]"
                                        @input="if (quantities[type.id] < 0) quantities[type.id] = 0"
                                        class="w-20 text-center rounded-xl border-muted/20 bg-bg text-text text-sm py-1.5 focus:border-accent focus:ring-accent shadow-inner">
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Étape 3 : Mode de récupération -->
                <div class="bg-content rounded-2xl border border-muted/10 p-6 shadow-sm" x-show="serviceId" x-cloak
                     x-transition:enter="transition ease-out duration-300">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-6 h-6 rounded-full bg-accent/10 text-accent text-xs font-bold flex items-center justify-center">3</span>
                        <h2 class="font-semibold text-base text-text">Mode de récupération</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="flex items-center gap-3 p-4 rounded-xl border border-muted/20 bg-bg cursor-pointer hover:border-accent/40 transition">
                            <input type="radio" name="delivery_mode" value="depot_physique" x-model="deliveryMode" class="text-accent focus:ring-accent">
                            <div>
                                <p class="text-sm font-semibold text-text">Dépôt en agence</p>
                                <p class="text-xs text-muted mt-0.5">Vous apportez vos articles.</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-4 rounded-xl border border-muted/20 bg-bg cursor-pointer hover:border-accent/40 transition">
                            <input type="radio" name="delivery_mode" value="collecte_livraison" x-model="deliveryMode" class="text-accent focus:ring-accent">
                            <div>
                                <p class="text-sm font-semibold text-text">Collecte à domicile</p>
                                <p class="text-xs text-muted mt-0.5">Nous venons les récupérer.</p>
                            </div>
                        </label>
                    </div>

                    <!-- Champs de collecte conditionnels -->
                    <div x-show="deliveryMode === 'collecte_livraison'" x-cloak 
                         x-transition:enter="transition ease-out duration-200"
                         class="mt-4 pt-4 border-t border-muted/10 space-y-4">
                        <div>
                            <label class="block text-xs uppercase tracking-wider text-muted font-semibold mb-1.5">Adresse de collecte</label>
                            <input type="text" name="pickup_address" x-model="pickupAddress"
                                placeholder="Quartier, rue, point de repère..."
                                class="w-full rounded-xl border-muted/20 bg-bg text-text text-sm px-4 py-2.5 focus:border-accent focus:ring-accent shadow-inner"
                                x-bind:required="deliveryMode === 'collecte_livraison'">
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-wider text-muted font-semibold mb-1.5">Numéro de téléphone</label>
                            <input type="text" name="pickup_phone" x-model="pickupPhone"
                                x-init="pickupPhone = $el.value"
                                value="{{ old('pickup_phone', session('phone')) }}"
                                class="w-full rounded-xl border-muted/20 bg-bg text-text text-sm px-4 py-2.5 focus:border-accent focus:ring-accent shadow-inner"
                                x-bind:required="deliveryMode === 'collecte_livraison'">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Colonne de droite : Récapitulatif Sticky (4 cols) -->
            <div class="lg:col-span-4 lg:sticky lg:top-6 space-y-4">
                <div class="bg-content rounded-2xl border border-muted/10 p-6 shadow-sm overflow-hidden">
                    <h2 class="font-semibold text-base text-text pb-4 border-b border-muted/10">Résumé de la commande</h2>
                    
                    <div class="py-4 space-y-3 text-sm">
                        <div class="flex justify-between text-muted">
                            <span>Service choisi :</span>
                            <span class="text-text font-medium text-right" x-text="activeServiceName || 'Aucun'"></span>
                        </div>
                        <div class="flex justify-between text-muted">
                            <span>Articles sélectionnés :</span>
                            <span class="text-text font-medium" x-text="totalItems"></span>
                        </div>
                        <div class="flex justify-between text-muted">
                            <span>Récupération :</span>
                            <span class="text-text font-medium text-right" x-text="deliveryMode === 'collecte_livraison' ? 'À domicile' : (deliveryMode === 'depot_physique' ? 'En agence' : 'Non défini')"></span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-muted/10 flex items-center justify-between mb-6">
                        <span class="text-sm font-medium text-muted">Total estimé</span>
                        <span class="font-bold text-xl text-text">
                            <span x-text="formatPrice(totalPrice)"></span> <span class="text-xs font-normal text-muted">FCFA</span>
                        </span>
                    </div>

                    <button type="submit"
                        :disabled="!canSubmit"
                        :class="canSubmit ? 'bg-accent hover:opacity-95 shadow-lg shadow-accent/10 cursor-pointer' : 'bg-muted/20 text-muted/40 cursor-not-allowed'"
                        class="w-full text-inverse font-medium text-sm py-4 rounded-full transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-basket-shopping"></i>
                        Valider la commande
                    </button>

                    <p class="text-center text-xs text-muted mt-4">
                        Connexion requise à l'étape suivante.
                    </p>
                </div>
            </div>

        </div>

        {{-- Champs cachés pour la soumission (Strictement identiques) --}}
        <template x-for="(qty, typeId) in quantities" :key="typeId">
            <template x-if="qty > 0">
                <div>
                    <input type="hidden" :name="'items[' + typeId + '][clothes_type_id]'" :value="typeId">
                    <input type="hidden" :name="'items[' + typeId + '][quantity]'" :value="qty">
                </div>
            </template>
        </template>
        </form>
    </div>
</div>

@endsection

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
                <h1 class="font-display text-2xl font-bold text-white sm:text-3xl lg:text-4xl">Lancer une commande</h1>
                <p class="mt-2 max-w-xl text-xs text-white/80 sm:text-sm">Sélectionnez vos prestations et composez votre panier en toute simplicité.</p>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-5xl px-6 py-12">
        @if ($errors->any())
        <div class="mb-6 rounded-xl border border-danger/20 bg-danger/10 p-4 text-sm text-danger" role="alert">
            <p class="font-semibold">La commande n'a pas pu être enregistrée.</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('orders.store') }}"
            x-data="orderForm({{ Js::from($services) }})"
            @submit="beforeSubmit">
            @csrf

            <!-- Grille principale en 2 colonnes -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- Colonne de gauche : Services & Articles (8 cols) -->
                <div class="lg:col-span-8 space-y-6" x-data="{ openService: {{ $services->first()?->id ?? 'null' }} }">

                    <!-- Étape 1 : Choix des services et articles associés -->
                    <div class="bg-content rounded-2xl border border-muted/10 p-6 shadow-sm space-y-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-6 h-6 rounded-full bg-accent/10 text-accent text-xs font-bold flex items-center justify-center">1</span>
                            <h2 class="font-semibold text-sm text-text sm:text-base">Sélectionnez vos prestations et articles</h2>
                        </div>
                        <p class="text-xs text-muted">Vous pouvez piocher des articles dans un ou plusieurs services différents pour votre commande.</p>

                        <!-- Liste des services en accordéons -->
                        <div class="space-y-4 pt-2">
                            @foreach ($services as $service)
                            <div class="border border-muted/15 rounded-xl overflow-hidden bg-bg transition-all">

                                <!-- En-tête du service (Cliquable) -->
                                <button type="button"
                                    @click="openService = openService === {{ $service->id }} ? null : {{ $service->id }}; loadServiceData({{ $service->id }})"
                                    class="w-full px-5 py-4 flex items-center justify-between bg-inverse/[0.01] hover:bg-inverse/[0.03] transition cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <span class="w-7 h-7 rounded-lg bg-accent/10 text-accent flex items-center justify-center font-bold text-xs">
                                            <i class="fa-solid fa-tag"></i>
                                        </span>
                                        <div class="text-left">
                                            <h3 class="text-sm font-semibold text-text">{{ $service->name }}</h3>
                                            <p class="text-xs text-muted">{{ $service->description ?? 'Cliquez pour voir les articles' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-accent/10 text-accent" x-show="getServiceItemsCount({{ $service->id }}) > 0" x-text="getServiceItemsCount({{ $service->id }}) + ' article(s)'" x-cloak></span>
                                        <i class="fa-solid fa-chevron-down text-xs text-muted transition-transform duration-200"
                                            :class="openService === {{ $service->id }} ? 'rotate-180' : ''"></i>
                                    </div>
                                </button>

                                <!-- Contenu des articles pour ce service -->
                                <div x-show="openService === {{ $service->id }}" x-cloak
                                    x-transition:enter="transition ease-out duration-200"
                                    class="p-5 border-t border-muted/15 bg-content space-y-4">

                                    <!-- Chargement -->
                                    <div x-show="loadingServiceId === {{ $service->id }}" class="text-center py-6 text-muted text-xs flex flex-col items-center gap-2">
                                        <i class="fa-solid fa-spinner animate-spin text-accent text-lg"></i>
                                        <span>Récupération des tarifs...</span>
                                    </div>

                                    <div x-show="loadingServiceId !== {{ $service->id }}">
                                        <div x-show="!clothesTypesPerService[{{ $service->id }}] || clothesTypesPerService[{{ $service->id }}].length === 0" class="text-center py-6 text-muted text-sm">
                                            Aucun article disponible pour ce service.
                                        </div>

                                        <div class="divide-y divide-muted/10">
                                            <template x-for="type in (clothesTypesPerService[{{ $service->id }}] || [])" :key="type.id">
                                                <div class="py-3 flex items-center justify-between first:pt-0 last:pb-0">
                                                    <div>
                                                        <p class="text-sm font-semibold text-text" x-text="type.name"></p>
                                                        <p class="text-xs text-muted mt-0.5" x-text="formatPrice(prices[{{ $service->id }}]?.[type.id]) + ' FCFA / unité'"></p>
                                                    </div>
                                                    <input type="number" min="0" inputmode="numeric"
                                                        :value="getQuantity({{ $service->id }}, type.id)"
                                                        @input="updateQuantity({{ $service->id }}, type.id, $event.target.value)"
                                                        class="w-20 text-center rounded-xl border-muted/20 bg-bg text-text text-sm py-1.5 focus:border-accent focus:ring-accent shadow-inner">
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Étape 2 : Mode de récupération -->
                    <div class="bg-content rounded-2xl border border-muted/10 p-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-6 h-6 rounded-full bg-accent/10 text-accent text-xs font-bold flex items-center justify-center">2</span>
                            <h2 class="font-semibold text-sm text-text sm:text-base">Mode de récupération</h2>
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
                        <!-- Champs de collecte conditionnels -->
                        <div x-show="deliveryMode === 'collecte_livraison'" x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            class="mt-4 pt-4 border-t border-muted/10 space-y-4">
                            <div>
                                <label class="block text-xs uppercase tracking-wider text-muted font-semibold mb-1.5">Adresse de collecte</label>
                                <input type="text" name="pickup_address" x-model="pickup_address"
                                    x-init="pickup_address = $el.value"
                                    value="{{ old('pickup_address', session('domicile')) }}"
                                    class="w-full rounded-xl border-muted/20 bg-bg text-text text-sm px-4 py-2.5 focus:border-accent focus:ring-accent shadow-inner"
                                    x-bind:required="deliveryMode === 'collecte_livraison'">
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-wider text-muted font-semibold mb-1.5">Numéro de téléphone</label>
                                <input type="text" name="pickup_phone" x-model="pickup_phone"
                                    x-init="pickup_phone = $el.value"
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
                        <h2 class="font-semibold text-sm text-text pb-4 border-b border-muted/10 sm:text-base">Résumé de la commande</h2>

                        <div class="py-4 space-y-3 text-sm">
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

                        @if(!auth()->check())
                        <p class="text-center text-xs text-muted mt-4">
                            Connexion requise à l'étape suivante.
                        </p>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Champs cachés dynamiques pour la soumission multi-service -->
            <template x-for="(types, sId) in selections" :key="sId">
                <template x-for="(qty, tId) in types" :key="tId">
                    <div>
                        <input type="hidden" :name="`items[${sId}_${tId}][service_id]`" :value="sId">
                        <input type="hidden" :name="`items[${sId}_${tId}][clothes_type_id]`" :value="tId">
                        <input type="hidden" :name="`items[${sId}_${tId}][quantity]`" :value="qty">
                    </div>
                </template>
            </template>
        </form>
    </div>
</div>

@endsection
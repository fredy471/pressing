@extends('layouts.app')
@section('title', 'Commande en attente de confirmation')
@section('content')

<div class="max-w-4xl mx-auto px-6 py-12">

    <!-- Fil d'Ariane -->
    <nav class="flex items-center gap-2 text-xs text-muted mb-6" aria-label="Fil d'Ariane">
        <a href="/" class="hover:text-text transition-colors">Accueil</a>
        <i class="fa-solid fa-chevron-right text-[10px] opacity-60" aria-hidden="true"></i>
        <a href="{{ route('client.orders.create') }}" class="hover:text-text transition-colors">Commander</a>
        <i class="fa-solid fa-chevron-right text-[10px] opacity-60" aria-hidden="true"></i>
        <span class="text-text font-medium">Commande en attente</span>
    </nav>

    <!-- En-tête de la page dynamique -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            @if(session('session_restored'))
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-accent/10 text-accent text-xs font-semibold mb-2">
                <i class="fa-solid fa-user-check text-[11px]"></i>
                <span>Session restaurée</span>
            </div>
            <h1 class="font-display text-2xl font-bold text-text sm:text-3xl">Finaliser votre commande</h1>
            <p class="mt-1.5 text-xs text-muted sm:text-sm">
                Vous avez préparé cette commande avant de vous connecter. Vérifiez le récapitulatif ci-dessous pour l'enregistrer.
            </p>
            @else
            <h1 class="font-display text-2xl font-bold text-text sm:text-3xl">Récapitulatif de votre commande</h1>
            <p class="mt-1.5 text-xs text-muted sm:text-sm">
                Vérifiez les détails de votre commande ci-dessous avant de procéder au paiement.
            </p>
            @endif
        </div>
    </div>
    @if ($errors->any())
    <div class="mb-8 rounded-2xl border border-danger/20 bg-danger/10 p-5 text-sm text-danger" role="alert">
        <p class="font-semibold flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation"></i>
            Une erreur est survenue lors de la validation
        </p>
        <ul class="mt-2 list-disc space-y-1 pl-5 text-xs">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (isset($pendingOrder) && $pendingOrder)
    @php $payload = $pendingOrder->payload; @endphp

    <!-- Section de mise en avant du panier en attente -->
    <section class="mb-10 rounded-2xl border border-accent/20 bg-accent/5 p-6 sm:p-8 shadow-sm">

        <div class="mb-6 flex items-center justify-between gap-4 border-b border-accent/15 pb-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-accent">Panier en attente</p>
                <h2 class="mt-1 font-display text-lg font-bold text-text sm:text-xl">Détails de la prestation</h2>
            </div>
            <div class="w-10 h-10 rounded-xl bg-accent/10 text-accent flex items-center justify-center text-lg">
                <i class="fa-solid fa-basket-shopping"></i>
            </div>
        </div>

        <!-- Grille d'informations clés -->
        <div class="grid gap-6 text-sm sm:grid-cols-3 mb-6">
            <div>
                <span class="block text-xs text-muted">Mode de récupération</span>
                <strong class="mt-1 block font-medium text-text">
                    @if (($payload['delivery_mode'] ?? '') === 'collecte_livraison')
                    <i class="fa-solid fa-truck text-accent mr-1"></i> Collecte à domicile
                    @else
                    <i class="fa-solid fa-store text-accent mr-1"></i> Dépôt en agence
                    @endif
                </strong>
            </div>

            @if (($payload['delivery_mode'] ?? '') === 'collecte_livraison')
            <div>
                <span class="block text-xs text-muted">Adresse de collecte</span>
                <p class="mt-1 font-medium text-text truncate" title="{{ $payload['pickup_address'] ?? '-' }}">
                    {{ $payload['pickup_address'] ?? 'Non spécifiée' }}
                </p>
            </div>
            <div>
                <span class="block text-xs text-muted">Téléphone de contact</span>
                <p class="mt-1 font-medium text-text">
                    {{ $payload['pickup_phone'] ?? 'Non spécifié' }}
                </p>
            </div>
            @endif
        </div>

        <!-- Liste des articles préparés -->
        @if (isset($lines) && $lines->isNotEmpty())
        <div class="bg-content/80 rounded-xl border border-muted/10 p-4 space-y-3 mb-6">
            <p class="text-xs font-semibold text-muted uppercase tracking-wider">Articles ajoutés</p>

            <div class="divide-y divide-muted/10 text-xs sm:text-sm">
                @foreach ($lines as $line)
                <div class="py-2.5 flex items-center justify-between gap-3 first:pt-0 last:pb-0">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-2 h-2 rounded-full bg-accent shrink-0"></span>
                        <span class="text-text font-medium truncate">{{ $line['clothes'] }}</span>
                        <span class="text-xs text-muted truncate">({{ $line['service'] }})</span>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="font-bold text-text">x{{ $line['quantity'] }}</span>
                        <span class="block text-xs text-muted">{{ number_format($line['subtotal'], 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Total -->
            <div class="flex items-center justify-between border-t border-muted/10 pt-3 text-sm">
                <span class="font-semibold text-text">Total estimé</span>
                <span class="font-bold text-text">{{ number_format($pendingOrder->total_amount, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>
        @endif

        <!-- Formulaire de validation finale -->
        <form method="POST" action="{{ route('client.payments.initiate', ['reference' => $pendingOrder->reference]) }}" class="pt-4 border-t border-accent/15 flex flex-col sm:flex-row items-center justify-between gap-4">
            @csrf
            <div>
                <span class="text-xs text-muted block">Prêt à valider ?</span>
                <p class="text-xs text-text">Cliquez sur le bouton pour soumettre votre commande définitivement.</p>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('client.orders.create') }}"
                    class="w-1/2 sm:w-auto text-center px-4 py-2.5 rounded-xl border border-muted/20 text-muted hover:text-text hover:bg-muted/10 text-xs font-medium transition">
                    Modifier
                </a>

                <button type="submit"
                    class="w-1/2 sm:w-auto inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 py-2 sm:px-6 sm:py-2.5 rounded-xl bg-accent text-inverse font-semibold text-xs sm:text-sm hover:opacity-95 transition shadow-sm cursor-pointer">
                    <i class="fa-solid fa-check text-[10px] sm:text-xs"></i>
                    <span>Confirmer la commande</span>
                </button>
            </div>
        </form>

    </section>
    @else
    <!-- État vide (Aucune commande en attente) -->
    <div class="bg-content rounded-2xl border border-muted/10 p-12 text-center space-y-4 shadow-sm">
        <div class="w-12 h-12 rounded-full bg-accent/10 text-accent flex items-center justify-center mx-auto text-lg">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </div>
        <div>
            <h3 class="font-semibold text-base text-text">Aucune commande en attente</h3>
            <p class="text-xs text-muted mt-1">Vous n'avez aucune session de commande non finalisée pour le moment.</p>
        </div>
        <div class="pt-2">
            <a href="{{ route('client.orders.create') }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-accent text-inverse font-medium text-sm hover:opacity-95 transition shadow-sm">
                Lancer une nouvelle commande
            </a>
        </div>
    </div>
    @endif

</div>

@endsection
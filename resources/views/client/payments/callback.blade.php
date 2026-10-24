@extends('layouts.app')

@section('title', 'Vérification du paiement')

@section('content')
<div class="mx-auto max-w-lg px-6 py-20 text-center">
    <div class="bg-content rounded-2xl border border-muted/10 p-8 shadow-sm space-y-6">
        <p id="payment-status-message" class="mt-3 text-xs text-muted" aria-live="polite"></p>
        <!-- Icône de chargement animée -->
        <div class="relative w-16 h-16 mx-auto flex items-center justify-center">
            <div class="absolute inset-0 rounded-full border-4 border-accent/20 animate-pulse"></div>
            <i class="fa-solid fa-spinner animate-spin text-accent text-2xl"></i>
        </div>

        <div class="space-y-2">
            <h1 class="font-display text-xl font-bold text-text">Vérification de votre paiement</h1>
            <p class="text-xs text-muted max-w-xs mx-auto">
                Cette page se mettra à jour automatiquement dès que la confirmation sera reçue.
            </p>
            <p id="payment-status-message" class="text-xs text-muted" aria-live="polite"></p>
        </div>
    </div>
</div>

<!-- Script de Polling -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // URLs relatives : aucun risque de http/https mélangé derrière un tunnel ou un proxy
        const statusUrl = @json(route('client.payments.status', $pendingOrder->reference, false));
        const ordersUrl = @json(route('client.orders.index', [], false));
        const messageEl = document.getElementById('payment-status-message');

        const INTERVAL_MS = 3000;
        const TIMEOUT_MS = 2 * 60 * 1000; // on arrête de vérifier après 2 minutes
        const MAX_FAILURES = 3; // erreurs consécutives tolérées

        const deadline = Date.now() + TIMEOUT_MS;
        let failures = 0;

        function showMessage(text) {
            if (messageEl) messageEl.textContent = text;
        }

        async function checkStatus() {
            try {
                const response = await fetch(statusUrl, {
                    headers: {
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                });

                if (!response.ok) throw new Error('HTTP ' + response.status);

                const data = await response.json();
                failures = 0;

                // Le webhook a confirmé le paiement
                if (data.status === 'confirmed' && data.redirect_url) {
                    window.location.href = data.redirect_url;
                    return;
                }
                if (data.status === 'annulee') {
                    alert('Le paiement a été annulé.');
                    window.location.href = ordersUrl;
                    return;
                }
            } catch (error) {
                failures++;
                console.warn('Vérification du statut échouée :', error);
                if (failures >= MAX_FAILURES) {
                    showMessage('Impossible de vérifier le paiement pour le moment. Actualisez la page dans un instant.');
                    return;
                }
            }

            if (Date.now() < deadline) {
                // La requête suivante ne part qu'une fois la précédente terminée
                setTimeout(checkStatus, INTERVAL_MS);
            } else {
                showMessage('La confirmation tarde. Actualisez cette page dans un instant : votre commande apparaîtra dès que le paiement sera validé.');
            }
        }

        checkStatus();
    });
</script>
@endpush
@endsection
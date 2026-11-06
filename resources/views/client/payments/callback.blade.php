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
        const ordersUrl = @json(route('client.orders.index', [], false));
        const messageEl = document.getElementById('payment-status-message');

        function showMessage(text) {
            if (messageEl) {
                messageEl.textContent = text;
            }
        }

        // TODO : écouter ici l'événement WebSocket
        // PaymentConfirmed, puis afficher la confirmation
        // et rediriger vers ordersUrl.
    });
</script>
@endpush
@endsection
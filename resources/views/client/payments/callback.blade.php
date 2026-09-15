@extends('layouts.app')

@section('title', 'Vérification du paiement')

@section('content')
<div class="mx-auto max-w-lg px-6 py-20 text-center">
    <div class="bg-content rounded-2xl border border-muted/10 p-8 shadow-sm space-y-6">
        
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
        </div>
    </div>
</div>

<!-- Script de Polling -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const reference = "{{ $pendingOrder->reference }}";
        const statusUrl = `/client/payments/${reference}/status`; // Adapter selon la structure de ta route de status

        const interval = setInterval(async () => {
            try {
                const response = await fetch(statusUrl);
                const data = await response.json();

                // Si le webhook a confirmé le paiement et renvoyé une URL de redirection
                if (data.status === 'confirmed' && data.redirect_url) {
                    clearInterval(interval); // On arrête le polling
                    window.location.href = data.redirect_url; // On redirige le client
                } else if (data.status === 'annulee') {
                    clearInterval(interval);
                    alert('Le paiement a été annulé.');
                    window.location.href = "{{ route('client.orders.index') }}";
                }
            } catch (error) {
                console.error('Erreur lors de la vérification du statut :', error);
            }
        }, 3000); // Vérifie toutes les 3 secondes
    });
</script>
@endpush
@endsection
<div x-data="{ showModal: true }" x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">

    <!-- Contenu de la modale -->
    <div class="bg-bg text-text rounded-sm border border-muted p-8 w-full max-w-md relative shadow-lg" @click.outside="showModal = false">

        <!-- Bouton de fermeture -->
        <button @click="showModal = false" class="absolute top-4 right-4 text-muted hover:text-text">
            ✕
        </button>

        <h2 class="text-2xl font-bold mb-6">Créer un compte</h2>

        <!-- Formulaire d'inscription Laravel Breeze / Jetstream ou perso -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-muted mb-2 uppercase tracking-wider">Nom</label>
                <input type="text" name="name" required class="w-full rounded-sm border border-muted bg-bg px-4 py-3 text-text">
            </div>

            <div>
                <label class="block text-xs font-semibold text-muted mb-2 uppercase tracking-wider">Email</label>
                <input type="email" name="email" required class="w-full rounded-sm border border-muted bg-bg px-4 py-3 text-text">
            </div>

            <div>
                <label class="block text-xs font-semibold text-muted sm-2 uppercase tracking-wider">Mot de passe</label>
                <input type="password" name="password" required class="w-full rounded-sm border border-muted bg-bg px-4 py-3 text-text">
            </div>

            <div>
                <label class="block text-xs font-semibold text-muted mb-2 uppercase tracking-wider">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" required class="w-full rounded-sm border border-muted bg-bg px-4 py-3 text-text">
            </div>

            <div class="pt-2">
                <x-button type="submit" class="w-full justify-center py-3 text-base sm:text-lg">
                    S'inscrire
                </x-button>
            </div>
        </form>
    </div>
</div>
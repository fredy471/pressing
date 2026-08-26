<x-admin-layout title="Nouvel agent">
    <h1 class="font-display font-bold text-2xl text-text">Nouvel utilisateur interne</h1>

    <form method="POST" action="{{ route('admin.users.store') }}" class="mt-8 max-w-lg space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-text mb-1.5">Nom complet</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full rounded-lg border-muted/20 bg-content focus:border-accent focus:ring-accent text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-text mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full rounded-lg border-muted/20 bg-content focus:border-accent focus:ring-accent text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-text mb-1.5">Téléphone</label>
            <input type="tel" name="phone" value="{{ old('phone') }}" required
                   class="w-full rounded-lg border-muted/20 bg-content focus:border-accent focus:ring-accent text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-text mb-1.5">Rôle</label>
            <select name="role" class="w-full rounded-lg border-muted/20 bg-content focus:border-accent focus:ring-accent text-sm">
                <option value="agent">Agent</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-accent text-inverse font-medium text-sm px-6 py-2.5 rounded-full hover:opacity-90 transition">
                Créer l'utilisateur
            </button>
            <a href="{{ route('admin.users.index') }}" class="text-muted text-sm px-6 py-2.5 hover:text-text transition">
                Annuler
            </a>
        </div>
    </form>
</x-admin-layout>
<x-admin-layout title="Détail utilisateur">
    <a href="{{ route('admin.users.index') }}" class="text-muted text-sm hover:text-text">← Retour</a>

    <div class="flex items-center justify-between mt-4">
        <div>
            <h1 class="font-display font-bold text-2xl text-text">{{ $user->name }}</h1>
            <p class="text-muted text-sm mt-1">{{ $user->email }} — {{ $user->phone }}</p>
        </div>
        <span class="text-xs font-semibold text-accent2 bg-accent2/10 px-3 py-1.5 rounded-full">
            {{ $user->getRoleNames()->first() }}
        </span>
    </div>

    <div class="bg-content rounded-xl border border-muted/10 p-6 mt-8 max-w-sm">
        <p class="text-muted text-xs uppercase tracking-wide font-mono">Commandes passées</p>
        <p class="font-display font-bold text-3xl text-text mt-2">{{ $user->orders_count }}</p>
    </div>
</x-admin-layout>
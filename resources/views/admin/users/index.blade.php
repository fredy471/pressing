<x-admin-layout title="Utilisateurs">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-display font-bold text-2xl text-text">Utilisateurs</h1>
            <p class="text-muted text-sm mt-1">Clients, agents et administrateurs.</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
            class="bg-accent text-inverse font-medium text-sm px-5 py-2.5 rounded-full hover:opacity-90 transition">
            + Nouvel agent
        </a>
    </div>

    <div class="bg-content rounded-xl border border-muted/10 mt-8 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-inverse/40 text-muted text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-6 py-3 font-mono">Nom</th>
                    <th class="text-left px-6 py-3 font-mono">Email</th>
                    <th class="text-left px-6 py-3 font-mono">Rôle</th>
                    <th class="text-right px-6 py-3 font-mono">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-muted/10">
                @foreach ($users as $user)
                <tr>
                    <td class="px-6 py-4 font-medium text-text">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-muted">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-semibold text-accent2">{{ $user->getRoleNames()->first() }}</span>
                    </td>
                    <td class="px-6 py-4 text-right relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="text-muted hover:text-text">
                            ⋮
                        </button>
                        <div x-show="open" x-cloak x-transition
                            class="absolute right-6 mt-2 w-40 bg-content border border-muted/15 rounded-lg shadow-lg py-1 z-10">
                            <a href="{{ route('admin.users.show', $user) }}" class="block px-4 py-2 text-sm text-text hover:bg-inverse/50">Voir le profil</a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="block px-4 py-2 text-sm text-text hover:bg-inverse/50">Modifier</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin-layout>
@extends('layouts.admin')

@section('title', 'Utilisateurs')

@section('content')
<div class="flex items-center justify-between">
    <div>
        <h1 class="font-display font-bold text-xl md:text-2xl text-text">Utilisateurs</h1>
        <p class="text-muted text-sm mt-1">Clients, agents et administrateurs.</p>
    </div>
    <a href="{{ route('admin.users.create') }}"
        class="bg-accent text-inverse font-medium text-sm px-5 py-2.5 rounded-full hover:opacity-90 transition">
        + Nouvel agent
    </a>
</div>

<div class="bg-content rounded-xl border border-muted/10 mt-8 overflow-hidden">
    <table class="w-full text-xs md:text-sm">
        <thead class="bg-inverse/40 text-muted text-[11px] md:text-xs font-semibold uppercase tracking-wide">
            <tr>
                <th class="text-left px-6 py-3 font-mono font-semibold">Nom</th>
                <th class="text-left px-6 py-3 font-mono font-semibold">Email</th>
                <th class="text-left px-6 py-3 font-mono font-semibold">Rôle</th>
                <th class="text-right px-6 py-3 font-mono font-semibold">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-muted/10">
            @forelse ($users as $user)
            <tr>
                <td class="px-6 py-4 font-medium text-text">{{ $user->name }}</td>
                <td class="px-6 py-4 text-text">{{ $user->email }}</td>
                <td class="px-6 py-4">
                    <span class="text-[10px] md:text-xs font-semibold text-accent2">{{ $user->getRoleNames()->first() }}</span>
                </td>
                <td class="px-6 py-4 text-right relative">
                    <div class="lg:col-span-1 flex justify-end pt-2 lg:pt-0 border-t border-muted/10 lg:border-t-0">
                        <a href="{{ route('admin.users.show', $user) }}" class="text-xs font-medium text-accent hover:underline bg-accent/10 px-3.5 py-1.5 rounded-lg transition">
                            Voir
                        </a>
                        <!-- <a href="{{ route('admin.users.show', $user) }}" class="block px-4 py-2 text-sm text-text hover:bg-inverse/50">Voir le profil</a> -->
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-8 text-center text-xs md:text-sm text-muted">
                    Aucun utilisateur trouvé.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3 flex items-center justify-between gap-4 px-4 pb-4">
    <div class="text-[11px] md:text-xs text-muted">
        @if ($users->total())
        Affichage de {{ $users->firstItem() }} à {{ $users->lastItem() }} sur {{ $users->total() }} utilisateurs
        @else
        Aucun utilisateur
        @endif
    </div>

    @if ($users->hasPages())
    <div class="flex justify-end">
        {{ $users->onEachSide(1)->links('pagination::simple-tailwind') }}
    </div>
    @endif
</div>
@endsection
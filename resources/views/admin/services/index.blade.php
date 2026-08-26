<x-admin-layout title="Services">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-display font-bold text-2xl text-text">Services</h1>
            <p class="text-muted text-sm mt-1">Gérez les prestations proposées par le pressing.</p>
        </div>
        <a href="{{ route('admin.services.create') }}"
            class="bg-accent text-inverse font-medium text-sm px-5 py-2.5 rounded-full hover:opacity-90 transition">
            + Nouveau service
        </a>
    </div>

    <div class="bg-content rounded-xl border border-muted/10 mt-8 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-inverse/40 text-muted text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-6 py-3 font-mono">Nom</th>
                    <th class="text-left px-6 py-3 font-mono w-1/3">Description</th>
                    <th class="text-left px-6 py-3 font-mono">Statut</th>
                    <th class="text-right px-6 py-3 font-mono">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-muted/10">
                @foreach ($services as $service)
                <tr>
                    <td class="px-6 py-4 font-medium text-text align-top">{{ $service->name }}</td>

                    {{-- Édition inline de la description --}}
                    <td class="px-6 py-4 align-top"
                        x-data="{ editing: false }">
                        <div x-show="!editing" @click="editing = true" class="cursor-pointer group">
                            <p class="text-muted group-hover:text-text transition">
                                {{ $service->description ?: 'Cliquez pour ajouter une description' }}
                            </p>
                        </div>

                        <form x-show="editing" x-cloak
                            method="POST" action="{{ route('admin.services.update-description', $service) }}"
                            @submit="editing = false"
                            class="flex flex-col gap-2">
                            @csrf
                            @method('PATCH')
                            <textarea name="description" rows="2" autofocus
                                class="w-full text-sm rounded-md border-muted/20 bg-bg focus:border-accent focus:ring-accent">{{ $service->description }}</textarea>
                            <div class="flex gap-2">
                                <button type="submit" class="text-accent text-xs font-semibold hover:underline">Enregistrer</button>
                                <button type="button" @click="editing = false" class="text-muted text-xs hover:underline">Annuler</button>
                            </div>
                        </form>
                    </td>

                    <td class="px-6 py-4 align-top">
                        @if ($service->is_active)
                        <span class="text-accent2 text-xs font-semibold">● Actif</span>
                        @else
                        <span class="text-muted text-xs font-semibold">● Inactif</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-right align-top">
                        <form method="POST" action="{{ route('admin.services.toggle-active', $service) }}"
                            x-data
                            @submit.prevent="if (confirm('{{ $service->is_active ? 'Désactiver' : 'Activer' }} ce service ?')) $el.submit()">
                            @csrf @method('PATCH')
                            <button class="text-accent text-sm font-medium hover:underline">
                                {{ $service->is_active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $services->links() }}</div>
</x-admin-layout>
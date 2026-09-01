@extends('layouts.admin')
@section('title', 'Types de vêtements')
@section('content')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-display font-bold text-2xl text-text">Types de vêtements</h1>
            <p class="text-muted text-sm mt-1">Les articles pouvant être pris en charge.</p>
        </div>
        <a href="{{ route('admin.clothes-types.create') }}"
            class="bg-accent text-inverse font-medium text-sm px-5 py-2.5 rounded-full hover:opacity-90 transition">
            + Nouveau type
        </a>
    </div>
    <div class="flex items-center jutify-between">
        <h3> <span class="font-bold"> Total </span>: {{ $count }}</h3>
    </div>

    <div class="bg-content rounded-xl border border-muted/10 mt-8 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-inverse/40 text-muted text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-6 py-3 font-mono">Nom</th>
                    <th class="text-left px-6 py-3 font-mono">Catégorie</th>
                    <th class="text-left px-6 py-3 font-mono">Statut</th>
                    <th class="text-right px-6 py-3 font-mono">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-muted/10">
                @foreach ($clothesTypes as $type)
                <tr>
                    <td class="px-6 py-4 font-medium text-text">{{ $type->name }}</td>
                    <td class="px-6 py-4">
                        <form method="POST" action="{{ route('admin.clothes-types.update-category', $type) }}"
                            class="flex items-center gap-2" x-data>
                            @csrf @method('PATCH')
                            <input type="text" name="category" value="{{ $type->category }}"
                                class="text-sm rounded-md border-muted/20 bg-bg w-32 py-1">
                            <button class="text-accent text-xs font-medium hover:underline">OK</button>
                        </form>
                    </td>
                    <td class="px-6 py-4">
                        @if ($type->is_active)
                        <span class="text-accent2 text-xs font-semibold">● Actif</span>
                        @else
                        <span class="text-muted text-xs font-semibold">● Inactif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form method="POST" action="{{ route('admin.clothes-types.toggle-active', $type) }}">
                            @csrf @method('PATCH')
                            <button class="text-accent text-sm font-medium hover:underline">
                                {{ $type->is_active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $clothesTypes->links() }}</div>
@endsection
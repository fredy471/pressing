@extends('layouts.agent')
@section('title', 'Commandes')
@section('content')

<h1 class="font-display font-bold text-2xl text-text">Commandes à traiter</h1>

<form method="GET" class="flex flex-wrap gap-3 mt-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Code de suivi..."
        class="rounded-lg border-muted/20 bg-content text-sm w-64">
    <select name="statut" class="rounded-lg border-muted/20 bg-content text-sm" onchange="this.form.submit()">
        <option value="">Statuts actifs</option>
        @foreach (['en_attente', 'recue', 'en_lavage', 'terminee', 'livree', 'annulee'] as $statut)
        <option value="{{ $statut }}" @selected(request('statut')===$statut)>{{ ucfirst(str_replace('_', ' ', $statut)) }}</option>
        @endforeach
    </select>

    <button class="bg-accent/10 text-accent font-medium text-sm px-5 py-2.5 rounded-xl hover:bg-accent/20 transition">
        Filtrer
    </button>
</form>

<div class="bg-content rounded-xl border border-muted/10 mt-6 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-inverse/40 text-muted text-xs uppercase tracking-wide">
            <tr>
                <th class="text-left px-6 py-3 font-mono">Code</th>
                <th class="text-left px-6 py-3 font-mono">Client</th>
                <th class="text-left px-6 py-3 font-mono">Statut</th>
                <th class="text-right px-6 py-3 font-mono">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-muted/10">
            @foreach ($orders as $order)
            <tr>
                <td class="px-6 py-4 font-mono text-text">{{ $order->tracking_code }}</td>
                <td class="px-6 py-4 text-text">{{ $order->user->name }}</td>
                <td class="px-6 py-4"><x-status-badge :statut="$order->statut" /></td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('agent.orders.show', $order) }}" class="text-accent text-sm font-medium hover:underline">Traiter</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<div class="mt-6">{{ $orders->links() }}</div>
@endsection
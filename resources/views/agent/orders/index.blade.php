@extends('layouts.agent')
@section('title', 'Commandes')
@section('content')

<h1 class="font-display font-bold text-2xl text-text">Commandes à traiter</h1>

<form method="GET" class="flex flex-wrap gap-3 mt-6" action="{{ route('agent.orders.index') }}">
    <input type="text" name="name" value="{{ request('name') }}" placeholder="Nom du client..."
        class="rounded-lg border-muted/20 bg-content text-sm w-52">

    <input type="text" name="email" value="{{ request('email') }}" placeholder="Email du client..."
        class="rounded-lg border-muted/20 bg-content text-sm w-56">

    <input type="text" name="tracking_code" value="{{ request('tracking_code') }}" placeholder="Code de suivi..."
        class="rounded-lg border-muted/20 bg-content text-sm w-52">

    <select name="status" class="rounded-lg border-muted/20 bg-content text-sm" onchange="this.form.submit()">
        <option value="">Statuts actifs</option>

        @foreach (['en_attente','recue','en_lavage','terminee','livree','annulee'] as $status)
        <option value="{{ $status }}" @selected(request('status')===$status)>
            {{ ucfirst(str_replace('_', ' ', $status)) }}
        </option>
        @endforeach
    </select>

    <button type="submit" class="bg-accent/10 text-accent font-medium text-sm px-5 py-2.5 rounded-xl hover:bg-accent/20 transition">
        Filtrer
    </button>

    @if (request()->hasAny(['name', 'email', 'tracking_code', 'status']))
    <a href="{{ url()->current() }}"
        class="text-sm px-4 py-2.5 rounded-xl border border-muted/20 hover:bg-content transition">
        Réinitialiser
    </a>
    @endif
</form>

<div class="bg-content rounded-xl border border-muted/10 mt-6 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-inverse/40 text-muted text-xs uppercase tracking-wide">
            <tr>
                <th class="text-left px-6 py-3 font-mono">Code</th>
                <th class="text-left px-6 py-3 font-mono">Client</th>
                <th class="text-left px-6 py-3 font-mono">Statut</th>
                <th class="text-right px-6 py-3 font-mono">Total</th>
                <th class="text-right px-6 py-3 font-mono">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-muted/10">
            @forelse ($orders as $order)
            <tr>
                <td class="px-6 py-4 font-mono text-text">{{ $order->tracking_code }}</td>
                <td class="px-6 py-4 text-text">{{ $order->user->name }}</td>
                <td class="px-6 py-4"><x-status-badge :status="$order->status" /></td>
                <td class="px-6 py-4 text-right font-mono font-semibold text-text">
                    {{ number_format($order->total_amount, 0, ',', ' ') }} F
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('agent.orders.show', $order) }}" class="text-accent text-sm font-medium hover:underline">Traiter</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted py-4">
                    Aucune commande à traiter.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $orders->links() }}</div>
@endsection
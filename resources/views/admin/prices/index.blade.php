@extends('layouts.admin')
@section('title', 'Tarifs')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="font-display font-bold text-xl md:text-2xl text-text">Grille tarifaire</h1>
        <p class="text-muted text-sm mt-1">Définissez un prix par combinaison de service et de type de vêtement.</p>
    </div>
</div>

@if ($missingCount > 0)
<div class="mb-6 bg-danger/10 border border-danger/20 text-danger rounded-lg px-4 py-3 text-xs md:text-sm flex items-center gap-2">
    <i class="fa-solid fa-triangle-exclamation"></i>
    {{ $missingCount }} combinaison(s) service X vêtement n'ont pas encore de prix défini <br> ces articles ne sont pas commandables par les clients tant qu'un tarif n'est pas renseigné.
</div>
@endif

@if ($services->isEmpty() || $clothesTypes->isEmpty())
<div class="bg-red-500/10 border border-red-500/20 rounded-2xl p-6 mt-8">
    <div class="flex items-start gap-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 bg-red-500/10 text-red-500">
            <i class="fa-solid fa-circle-exclamation text-sm"></i>
        </div>
        <div>
            <p class="font-semibold text-red-500 text-sm md:text-base mb-1">Impossible d'afficher la grille tarifaire</p>
            <p class="text-red-500/80 text-[11px] md:text-xs leading-relaxed">
                @if ($services->isEmpty())
                Aucun service n'est activé. Veuillez <a href="{{ route('admin.services.index') }}" class="underline hover:opacity-80">activer au moins un service</a> pour continuer.
                @elseif ($clothesTypes->isEmpty())
                Aucun type de vêtement n'est activé. Veuillez <a href="{{ route('admin.clothes-types.index') }}" class="underline hover:opacity-80">activer au moins un type de vêtement</a> pour continuer.
                @endif
            </p>
        </div>
    </div>
</div>
@else
<form method="POST" action="{{ route('admin.prices.update') }}" class="mt-8"
    x-data="{ dirty: false }"
    @input="dirty = true">
    @csrf
    @method('PATCH')

    <!-- Conteneur avec scroll horizontal optimisé -->
    <div class="bg-content rounded-2xl border border-muted/10 overflow-x-auto shadow-sm">
        <table class="w-full text-xs md:text-sm border-collapse">
            <thead class="bg-inverse/20 text-muted text-[11px] md:text-xs font-mono font-semibold uppercase tracking-wider border-b border-muted/10">
                <tr>
                    <th class="text-left px-6 py-4 font-semibold sticky left-0 bg-content z-10 shadow-[2px_0_5px_rgba(0,0,0,0.02)]">Vêtement</th>
                    @foreach ($services as $service)
                    <th class="text-left px-6 py-4 font-semibold whitespace-nowrap">{{ $service->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-muted/10">
                @foreach ($clothesTypes as $clothesType)
                <tr class="hover:bg-inverse/[0.02] transition-colors">
                    <td class="px-6 py-4 font-medium text-text sticky left-0 bg-content z-10 shadow-[2px_0_5px_rgba(0,0,0,0.02)] whitespace-nowrap">
                        {{ $clothesType->name }}
                    </td>
                    @foreach ($services as $service)
                    @php $key = $service->id . '_' . $clothesType->id; @endphp
                    <td class="px-6 py-3">
                        <input type="number" step="0.01" min="1"
                            name="prices[{{ $key }}][price]"
                            value="{{ $prices[$key]->price ?? '' }}"
                            placeholder="—"
                            class="w-24 rounded-md text-sm font-mono py-1.5 placeholder:text-xs md:placeholder:text-sm
                  {{ isset($prices[$key]) && filled($prices[$key]->price) ? 'border-muted/20 bg-bg' : 'border-danger/40 bg-danger/5' }}">
                        <input type="hidden" name="prices[{{ $key }}][service_id]" value="{{ $service->id }}">
                        <input type="hidden" name="prices[{{ $key }}][clothes_type_id]" value="{{ $clothesType->id }}">
                    </td>
                    <!-- <td class="px-6 py-3 whitespace-nowrap">
                        <div class="relative inline-block w-28">
                            <input type="number" step="1" min="0"
                                name="prices[{{ $key }}][price]"
                                value="{{ $prices[$key]->price ?? '' }}"
                                placeholder="—"
                                class="w-full rounded-xl border-muted/20 bg-bg text-text text-sm font-mono py-2 pl-3 pr-6 focus:border-accent focus:ring-accent shadow-inner">
                            <span class="absolute right-1 top-1/2 -translate-y-1/2 text-xs text-muted font-mono pointer-events-none">F</span>
                        </div>
                        <input type="hidden" name="prices[{{ $key }}][service_id]" value="{{ $service->id }}">
                        <input type="hidden" name="prices[{{ $key }}][clothes_type_id]" value="{{ $clothesType->id }}">
                    </td> -->
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Barre d'actions fixe / bas de page -->
    <div class="mt-6 flex items-center gap-4">
        <button type="submit" class="bg-accent text-inverse font-medium text-xs md:text-sm px-6 py-2.5 rounded-full hover:opacity-90 transition shadow-sm">
            Enregistrer les tarifs
        </button>
        <span x-show="dirty" x-cloak x-transition class="text-[11px] md:text-xs text-muted italic flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
            Modifications non enregistrées
        </span>
    </div>
</form>

<div class="mt-6 p-4 text-[11px] md:text-xs text-muted leading-relaxed ">
    <p class="font-semibold text-text text-sm md:text-base mb-2">N.B. concernant les tarifs</p>
    <ul class="list-disc list-inside space-y-1">
        <li>Chaque montant doit être renseigné : un tarif nul ou vide n'est pas autorisé et ne peut pas être enregistré.</li>
        <li>Une combinaison sans tarif ne sera pas affichée aux clients et ne pourra pas être commandée.</li>
        <li>Tous les montants sont exprimés en FCFA.</li>
    </ul>
</div>
@endif
@endsection
<x-admin-layout title="Tarifs">
    <h1 class="font-display font-bold text-2xl text-text">Grille tarifaire</h1>
    <p class="text-muted text-sm mt-1">Un prix par combinaison service × type de vêtement.</p>


    <form method="POST" action="{{ route('admin.prices.update') }}" class="mt-8"
        x-data="{ dirty: false }"
        @input="dirty = true">
        @csrf
        @method('PATCH')

        <div class="bg-content rounded-xl border border-muted/10 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-inverse/40 text-muted text-xs uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-6 py-3 font-mono sticky left-0 bg-inverse/40">Vêtement</th>
                        @foreach ($services as $service)
                        <th class="text-left px-6 py-3 font-mono whitespace-nowrap">{{ $service->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-muted/10">
                    @foreach ($clothesTypes as $clothesType)
                    <tr>
                        <td class="px-6 py-3 font-medium text-text sticky left-0 bg-content">{{ $clothesType->name }}</td>
                        @foreach ($services as $service)
                        @php $key = $service->id . '_' . $clothesType->id; @endphp
                        <td class="px-6 py-3">
                            <input type="number" step="1" min="0"
                                name="prices[{{ $key }}][price]"
                                value="{{ $prices[$key]->price ?? '' }}"
                                placeholder="—"
                                class="w-24 rounded-md border-muted/20 bg-bg text-sm font-mono py-1.5">
                            <input type="hidden" name="prices[{{ $key }}][service_id]" value="{{ $service->id }}">
                            <input type="hidden" name="prices[{{ $key }}][clothes_type_id]" value="{{ $clothesType->id }}">
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex items-center gap-4">
            <button type="submit" class="bg-accent text-inverse font-medium text-sm px-6 py-2.5 rounded-full hover:opacity-90 transition">
                Enregistrer les tarifs
            </button>
            <span x-show="dirty" x-cloak x-transition class="text-sm text-muted italic">
                Modifications non enregistrées
            </span>
        </div>
    </form>
</x-admin-layout>
@props(["title", "text", "minimumPrice", "assets", "option" => null])


<div class="rounded-3xl bg-white dark:bg-[#16211D] shadow-sm border border-gray-100 flex flex-col justify-between overflow-hidden transform transition hover:-translate-y-1 hover:shadow-md group">
    <div class="h-48 overflow-hidden relative">
        <img src="{{ asset($assets) }}" alt="Lavage et repassage" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
    </div>

    <div class="p-8 flex-1 flex flex-col justify-between">
        <div>
            <h3 class="font-display text-xl font-bold text-[var(--color-text)] mb-3">
                <!-- Lavage et repassage -->
                {{ $title }}

            </h3>
            <p class="font-[family-name:var(--font-sans)] text-sm text-text leading-relaxed mb-6">
                {{ $text }}
                <!-- Lavage complet en machine, séchage optimal et repassage soigné pour vos habits du quotidien. -->
            </p>
        </div>
        <div>
            <div class="mb-6">
                <span class="text-xs text-[var(--color-muted)] block uppercase tracking-wider">Tarif</span>
                <span class="font-display text-2xl font-bold text-[var(--color-accent)]">
                    dès {{ $minimumPrice }}
                </span>
                <span class="text-xs text-[var(--color-muted)]">/ pièce</span>
            </div>
            <a href="{{ route('services') }} " class="w-full inline-flex items-center justify-center font-[family-name:var(--font-sans)] font-medium text-sm bg-[var(--color-accent)] text-white py-3 px-4 rounded-xl shadow-sm hover:opacity-95 transition-opacity dark:text-[#000]">
                Commander
            </a>
        </div>
    </div>
</div>

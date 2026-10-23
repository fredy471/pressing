@extends('layouts.app')
@section('title', 'Contact')
@section('content')
<!---contact-->
<section id="contact" class="scroll-mt-20 py-20 px-4 sm:px-6 lg:px-8 bg-bg text-text relative overflow-hidden">
    <div class="max-w-6xl mx-auto">

        <!-- En-tête épuré et moderne -->
        <div class="max-w-2xl mb-16">
            <span class="text-xs font-semibold tracking-widest uppercase text-accent mb-3 block">Parlons de vos vêtements</span>
            <h2 class="text-3xl sm:text-4xl font-semibold text-text font-display tracking-[-0.025em] mb-4">Une question, un besoin de pressing ?</h2>
            <p class="text-text text-base sm:text-lg leading-relaxed">
                Passez nous voir à l'atelier ou écrivez-nous directement. Nous prenons le plus grand soin de votre linge à Cotonou.
            </p>
        </div>

        <!-- Layout asymétrique ultra-propre -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-start">

            <!-- Colonne de gauche : Coordonnées en mode minimaliste (5 colonnes) -->
            <div class="lg:col-span-5 space-y-8 lg:pr-6 lg:border-r lg:border-muted p-4">

                <div class="border-l-2 border-accent pl-5 space-y-1" data-aos="fade-up">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-muted">Notre Atelier</h3>
                    <p class="text-text font-medium text-base">Quartier Vodjè, Cotonou</p>
                    <p class="text-muted text-sm">Bénin</p>
                </div>

                <div class="border-l-2 border-muted pl-5 space-y-1" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-muted">Horaires d'ouverture</h3>
                    <p class="text-text font-medium text-base">Lundi au Samedi : 08h30 - 19h00</p>
                    <p class="text-muted text-sm">Service fermé le dimanche</p>
                </div>

                <div class="border-l-2 border-muted pl-5 space-y-1" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-muted">Contact direct</h3>
                    <p class="text-text font-medium text-base">+229 01 53 54 03 23</p>
                    <p class="text-muted text-sm">contact@pressopessing.bj</p>
                </div>

                <!-- Action WhatsApp -->
                <div class="pt-4">
                    <a href="https://wa.me/2290153540323" target="_blank" class="inline-flex items-center gap-3 text-sm font-semibold text-text hover:text-accent transition duration-200 group">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full border border-muted group-hover:border-accent transition duration-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.124-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                            </svg>
                        </span>
                        <span>Discuter directement sur WhatsApp</span>
                    </a>
                </div>

            </div>


            <div class="lg:col-span-7">
                <div class="rounded-sm border border-muted p-8 sm:p-10 bg-bg shadow-sm">
                    <form action="{{ route('contact.form') }}" method="POST" class="space-y-6" x-data="{ loading: false }" @submit.prevent="loading = true; $el.submit();">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <input type="text" name="website" id="website" class="hidden">
                                <label for="name" class="block text-xs font-semibold text-text mb-2 uppercase tracking-wider">Votre nom</label>
                                <input type="text" id="name" name="name" required placeholder="Ex: Jean Dupont"
                                    class="w-full rounded-sm border border-muted bg-bg px-4 py-3 text-[0.86rem] text-text placeholder:text-muted focus:border-text focus:outline-none transition duration-200">
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-semibold text-text mb-2 uppercase tracking-wider">Contact (Email)</label>
                                <input type="email" id="email" name="email" required placeholder="Ex: jonedoe@gmail.com"
                                    class="w-full rounded-sm border border-muted bg-bg px-4 py-3 text-[0.86rem] text-text placeholder:text-muted focus:border-text focus:outline-none transition duration-200">
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-xs font-semibold text-text mb-2 uppercase tracking-wider">Sujet de votre demande</label>
                            <input
                                type="text"
                                id="subject"
                                name="subject"
                                placeholder="Ex: Collecte à domicile / Question tarif"
                                class="w-full rounded-sm border border-muted bg-bg px-4 py-3 text-[0.86rem] text-text placeholder:text-muted focus:border-text focus:outline-none transition duration-200">
                        </div>

                        <div>
                            <label for="message" class="block text-xs font-semibold text-text mb-2 uppercase tracking-wider">Votre message</label>
                            <textarea
                                id="message"
                                name="message"
                                rows="5"
                                required
                                placeholder="Détaillez vos besoins (type de vêtements, nombre de pièces...)"
                                class="w-full rounded-sm border border-muted bg-bg px-4 py-3 text-[0.86rem] text-text placeholder:text-muted focus:border-text focus:outline-none transition duration-200 resize-none"></textarea>
                        </div>

                        <div class="pt-2">
                            <x-button type="submit" variant="primary" class="w-full justify-center py-3" x-bind:disabled="loading">
                                <!-- Texte normal -->
                                <span x-show="!loading">Envoyer la demande</span>

                                <!-- Texte et icône de chargement au clic -->
                                <span x-show="loading" x-cloak class="flex items-center gap-2">
                                    <i class="fa-solid fa-spinner animate-spin"></i>
                                    <span>Envoi en cours...</span>
                                </span>
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
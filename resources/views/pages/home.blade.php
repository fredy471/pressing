@extends('layouts.app')
@section('title', 'Acceuil')
@section('content')
    <section class="bg-bg py-16 sm:py-20 lg:py-32" id="home">
        <div class="container mx-auto px-4 md:px-8 flex flex-col gap-12 lg:grid lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
            <div>
                <p class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-accent">Pressing en ligne</p>
                <h1 class="mb-6 font-display text-4xl leading-tight text-text sm:text-5xl  tracking-[-0.02em] ">
                    Rendez vos vêtements propre<br> et impeccable sans bouger<br> de chez vous
                </h1>
                <p class="mb-8 max-w-2xl text-lg text-text">
                    Commandez votre lavage, repassage ou nettoyage premium en quelques clics. Collecte,
                    suivi en temps réel et notifications WhatsApp automatiques
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-sm border border-transparent bg-accent px-5 py-2.5 text-[0.86rem] font-semibold text-inverse transition duration-200 hover:bg-accent2">
                        Créer un compte
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-sm border border-muted px-5 py-2.5 text-[0.86rem] font-semibold text-text transition duration-200 hover:border-text">
                        Se connecter
                    </a>
                </div>
            </div>

            <div class="relative z-10 bg-content text-text rounded shadow-[0_30px_60px_-20px_rgba(0,0,0,0.55)] -rotate-[1.4deg] p-0">

                <!-- En-tête du ticket -->
                <div class="px-[26px] pt-[22px] pb-4 border-b border-dashed border-text flex justify-between items-start">
                    <x-pressing-logo />
                    <div class="font-[family-name:var(--mono)] text-[0.72rem] text-text text-right">
                        Code de suivi<b class="block text-[0.95rem] tracking-[0.04em]">PRS-48211</b>
                    </div>
                </div>
                <!-- Corps du ticket -->
                <div class="px-[26px] py-5">
                    <div class="flex justify-between text-[0.86rem] py-[6px] text-muted">
                        <span>Service</span><b class="text-[var(--color-text)]">Nettoyage Premium</b>
                    </div>
                    <div class="flex justify-between text-[0.86rem] py-[6px] text-[var(--color-muted)]">
                        <span>Articles</span><b class="text-[var(--color-text)]">4 pièces</b>
                    </div>
                    <div class="flex justify-between text-[0.86rem] py-[6px] text-[var(--color-muted)]">
                        <span>Dépôt </span><b class="text-[var(--color-text)]">XX XX 2026</b>
                    </div>

                    <!-- Stepper -->
                    <div class="flex items-center mt-5 mb-1.5">
                        <!-- Étape 1 : En attente -->
                        <div class="flex flex-col items-center flex-1 relative group">
                            <div class="w-[11px] h-[11px] rounded-full bg-transparent border-2 border-[var(--color-muted)] z-20"></div>
                            <div class="absolute top-[5px] left-1/2 w-full h-[2px] bg-[var(--color-muted)] z-10"></div>
                            <span class="font-[family-name:var(--mono)] text-[0.6rem] mt-2 text-center text-[var(--color-muted)] max-w-[60px]">En attente</span>
                        </div>

                        <!-- Étape 2 : Reçue -->
                        <div class="flex flex-col items-center flex-1 relative group">
                            <div class="w-[11px] h-[11px] rounded-full bg-[var(--color-accent)] border-2 border-[var(--color-muted)]/50 z-20"></div>
                            <div class="absolute top-[5px] left-1/2 w-full h-[2px] bg-[var(--color-accent)] z-10"></div>
                            <span class="font-[family-name:var(--mono)] text-[0.6rem] mt-2 text-center text-[var(--color-muted)] max-w-[60px]">Reçue</span>
                        </div>

                        <!-- Étape 3 : En lavage (Active) -->
                        <div class="flex flex-col items-center flex-1 relative group">
                            <div class="w-[11px] h-[11px] rounded-full bg-[var(--color-accent)] border-2 border-[var(--color-accent)] z-20"></div>
                            <div class="absolute top-[5px] left-1/2 w-full h-[2px] bg-[var(--color-accent)] z-10"></div>
                            <span class="font-[family-name:var(--mono)] text-[0.6rem] mt-2 text-center text-[var(--color-muted)] max-w-[60px]">En lavage</span>
                        </div>

                        <!-- Étape 4 : Terminée -->
                        <div class="flex flex-col items-center flex-1 relative group">
                            <div class="w-[11px] h-[11px] rounded-full bg-[var(--color-accent2)] border-2 border-[var(--color-muted)]/50 z-20"></div>
                            <div class="absolute top-[5px] left-1/2 w-full h-[2px] bg-[var(--color-accent2)] z-10"></div>
                            <span class="font-[family-name:var(--mono)] text-[0.6rem] mt-2 text-center text-[var(--color-muted)] max-w-[60px]">Terminée</span>
                        </div>

                        <!-- Étape 5 : Livrée (Last) -->
                        <div class="flex flex-col items-center flex-1 relative group">
                            <div class="w-[11px] h-[11px] rounded-full bg-[var(--color-accent2)] border-2 border-[var(--color-accent2)] z-20"></div>
                            <span class="font-[family-name:var(--mono)] text-[0.6rem] mt-2 text-center text-[var(--color-muted)] max-w-[60px]">Livrée</span>
                        </div>
                    </div>
                </div>

                <!-- Pied du ticket -->
                <div class="px-[26px] py-[14px] bg-muted font-[family-name:var(--mono)] text-[0.7rem] text-[var(--ink-soft)] flex justify-between rounded-b">
                    <span>Notification WhatsApp active</span><span></span>
                </div>
            </div>
        </div>
    </section>

    <!-- About section -->
    <section id="about" class="bg-bg py-16 md:py-24 text-text">
        <div class="container mx-auto px-4 md:px-8">
            <!-- Grille principale en 12 colonnes -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 md:gap-16 items-center">

                <!-- Bloc Texte : Occupe 7 colonnes sur tablette/PC -->
                <div class="md:col-span-7 space-y-8">
                    <h2 class="font-display text-3xl sm:text-4xl md:text-5xl lg:text-6xl text-text leading-tight tracking-[-0.02em]">
                        Le pressing pensé pour votre époque.
                    </h2>

                    <div class="text-text text-base sm:text-lg space-y-6 leading-relaxed font-sans">
                        <p>
                            Presso vous offre un service de pressing simple, rapide et fiable.<br>
                            Nous prenons soin de vos vêtements avec attention, en respectant chaque matière, tout en vous permettant de gagner du temps grâce à la commande et au suivi en ligne.
                        </p>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-8 pt-6 border-t border-[var(--color-muted)]/20">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-7 h-7 text-[var(--color-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-xl text-text mb-1">Technologie Aqua-Care</h4>
                                <p class="text-text text-base">Des procédés de nettoyage à l'eau bio-dégradables, sains pour vous et pour la planète.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-7 h-7 text-[var(--color-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-xl text-text mb-1">Suivi en temps réel</h4>
                                <p class="text-text text-base">Une application unique pour gérer vos commandes, suivre l'étape de nettoyage et planifier la livraison.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bloc Image : Occupe 5 colonnes sur tablette/PC (7 + 5 = 12) -->
                <div class="md:col-span-5 relative h-full min-h-[350px] md:min-h-[450px] w-full">
                    <div class="absolute inset-0 bg-cover bg-center rounded-[20px] shadow-lg"
                        style="background-image: url('{{ asset('images/img1.jpg') }}');">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Section : Comment Procéder (Design Bento / Timeline Modern) -->
    <section id="process" class="py-24 md:py-32 bg-bg text-text relative">
        <div class="container mx-auto px-4 md:px-8">

            <!-- En-tête de section avec badge -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 pb-6 border-b border-black/5 dark:border-white/10">
                <div class="space-y-3">
                    <span class="text-xs uppercase tracking-widest font-bold text-[var(--color-accent)]">
                        Guide d'utilisation
                    </span>
                    <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-text">
                        Comment ça marche ?
                    </h2>
                </div>
                <p class="text-text/70 text-base max-w-md mt-4 md:mt-0 font-sans">
                    Quatre étapes simples, de la sélection de vos articles jusqu'à la livraison à votre porte.
                </p>
            </div>

            <!-- Grille asymétrique style Bento / 4 étapes -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- Étape 1 : Le point de départ (Mise en avant visuelle) -->
                <div class="bg-gradient-to-br from-[var(--color-accent)] to-[var(--color-accent)]/80 text-white rounded-3xl p-8 flex flex-col justify-between shadow-xl relative overflow-hidden group">
                    <div class="absolute -right-6 -bottom-6 text-9xl font-extrabold font-display opacity-10 select-none pointer-events-none">
                        01
                    </div>
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-xl mb-8 text-white">
                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        </div>
                        <span class="text-xs uppercase tracking-wider font-semibold opacity-80">Étape 01</span>
                        <h4 class="font-semibold text-2xl mt-1 mb-3">Choisissez vos services</h4>
                        <p class="text-white/80 text-sm leading-relaxed">
                            Naviguez sur la page services et sélectionnez vos vêtements et traitements en un clic.
                        </p>
                    </div>
                    <div class="mt-8 pt-4 border-t border-white/20 flex items-center text-xs font-medium tracking-wide opacity-90">
                        <span>Catalogue interactif</span>
                    </div>
                </div>

                <!-- Étape 2 -->
                <div class="bg-white/60 dark:bg-white/[0.03] border border-black/5 dark:border-white/10 rounded-3xl p-8 flex flex-col justify-between transition-all duration-300 hover:border-[var(--color-accent)]/50 hover:shadow-lg relative overflow-hidden group">
                    <div class="absolute -right-6 -bottom-6 text-9xl font-extrabold font-display text-[var(--color-accent)]/10 select-none pointer-events-none group-hover:scale-110 transition-transform duration-300">
                        02
                    </div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 rounded-2xl bg-[var(--color-accent)]/10 text-[var(--color-accent)] flex items-center justify-center text-xl mb-8">
                            <i class="fa fa-calculator" aria-hidden="true"></i>
                        </div>
                        <span class="text-xs uppercase tracking-wider font-semibold text-[var(--color-accent)]">Étape 02</span>
                        <h4 class="font-semibold text-2xl text-text mt-1 mb-3">Calculez le prix</h4>
                        <p class="text-text/70 text-sm leading-relaxed">
                            Notre simulateur calcule instantanément le montant total selon vos choix.
                        </p>
                    </div>
                    <div class="mt-8 pt-4 border-t border-black/5 dark:border-white/10 text-xs font-medium text-text/50 relative z-10">
                        <span>Transparence totale</span>
                    </div>
                </div>

                <!-- Étape 3 -->
                <div class="bg-white/60 dark:bg-white/[0.03] border border-black/5 dark:border-white/10 rounded-3xl p-8 flex flex-col justify-between transition-all duration-300 hover:border-[var(--color-accent)]/50 hover:shadow-lg relative overflow-hidden group">
                    <div class="absolute -right-6 -bottom-6 text-9xl font-extrabold font-display text-[var(--color-accent)]/10 select-none pointer-events-none group-hover:scale-110 transition-transform duration-300">
                        03
                    </div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 rounded-2xl bg-[var(--color-accent)]/10 text-[var(--color-accent)] flex items-center justify-center text-xl mb-8">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </div>
                        <span class="text-xs uppercase tracking-wider font-semibold text-[var(--color-accent)]">Étape 03</span>
                        <h4 class="font-semibold text-2xl text-text mt-1 mb-3">Renseignez vos infos</h4>
                        <p class="text-text/70 text-sm leading-relaxed">
                            Indiquez votre adresse de collecte et l'horaire qui vous arrange.
                        </p>
                    </div>
                    <div class="mt-8 pt-4 border-t border-black/5 dark:border-white/10 text-xs font-medium text-text/50 relative z-10">
                        <span>Livraison sur mesure</span>
                    </div>
                </div>

                <!-- Étape 4 -->
                <div class="bg-white/60 dark:bg-white/[0.03] border border-black/5 dark:border-white/10 rounded-3xl p-8 flex flex-col justify-between transition-all duration-300 hover:border-[var(--color-accent)]/50 hover:shadow-lg relative overflow-hidden group">
                    <div class="absolute -right-6 -bottom-6 text-9xl font-extrabold font-display text-[var(--color-accent)]/10 select-none pointer-events-none group-hover:scale-110 transition-transform duration-300">
                        04
                    </div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 rounded-2xl bg-[var(--color-accent)]/10 text-[var(--color-accent)] flex items-center justify-center text-xl mb-8">
                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                        </div>
                        <span class="text-xs uppercase tracking-wider font-semibold text-[var(--color-accent)]">Étape 04</span>
                        <h4 class="font-semibold text-2xl text-text mt-1 mb-3">Confirmez & payez</h4>
                        <p class="text-text/70 text-sm leading-relaxed">
                            Validez votre commande et suivez chaque étape du nettoyage en direct.
                        </p>
                    </div>
                    <div class="mt-8 pt-4 border-t border-black/5 dark:border-white/10 text-xs font-medium text-text/50 relative z-10">
                        <span>Paiement sécurisé</span>
                    </div>
                </div>

            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-16">
                <a href="{{ route('services') }}" class="w-full sm:w-auto inline-flex items-center justify-between gap-6 pl-8 pr-2 py-2 rounded-full font-semibold text-inverse bg-[var(--color-accent)] shadow-lg shadow-[var(--color-accent)]/25 hover:opacity-95 transition-all duration-300 group">
                    <span>Commencer ma commande</span>
                    <span class="w-10 h-10 rounded-full bg-white text-[var(--color-accent)] flex items-center justify-center transform group-hover:scale-105 transition-transform">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>
                    </span>
                </a>
            </div>

        </div>
    </section>

    <!-- Nos services section -->
    <section class="py-20 bg-content relative overflow-hidden" id="services">
        <div class="container mx-auto px-4 md:px-8">

            <!-- En-tête de section -->
            <div class="max-w-3xl mx-auto text-center mb-16">
                <span class="inline-block text-xs font-semibold tracking-widest uppercase px-3 py-1 rounded-full bg-[var(--color-accent)]/10 text-[var(--color-accent)] mb-4">
                    Nos Services
                </span>
                <h2 class="font-display text-3xl md:text-4xl lg:text-5xl text-[var(--color-text)] tracking-[-0.025em] leading-[1.15] mb-4">
                    Un service pour chaque besoin
                </h2>
                <p class="font-[family-name:var(--font-sans)] text-sm md:text-base text-text tracking-normal leading-relaxed">
                    Chaque commande ne contient qu'un seul type de service — vous pouvez en créer plusieurs si besoin
                </p>
            </div>

            <!-- Grille des cartes de services (3 colonnes) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12 max-w-6xl mx-auto">

                <!-- Carte 1 : Lavage et repassage -->
                <div class="rounded-3xl bg-white dark:bg-[#16211D] shadow-sm border border-gray-100 flex flex-col justify-between overflow-hidden transform transition hover:-translate-y-1 hover:shadow-md group">
                    <!-- Image d'illustration du service -->
                    <div class="h-48 overflow-hidden relative">
                        <img src=" {{ asset('images/lavage-repassage.jpg') }}" alt="Lavage et repassage" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    </div>

                    <div class="p-8 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-display text-xl font-bold text-[var(--color-text)] mb-3">Lavage et repassage</h3>
                            <p class="font-[family-name:var(--font-sans)] text-sm text-text leading-relaxed mb-6">
                                Lavage complet en machine, séchage optimal et repassage soigné pour vos habits du quotidien.
                            </p>
                        </div>
                        <div>
                            <div class="mb-6">
                                <span class="text-xs text-[var(--color-muted)] block uppercase tracking-wider">Tarif</span>
                                <span class="font-display text-2xl font-bold text-[var(--color-accent)]">dès 1500 FCFA</span>
                                <span class="text-xs text-[var(--color-muted)]">/ pièce</span>
                            </div>
                            <a href="{{ route('services') }} " class="w-full inline-flex items-center justify-center font-[family-name:var(--font-sans)] font-medium text-sm bg-[var(--color-accent)] text-white py-3 px-4 rounded-xl shadow-sm hover:opacity-95 transition-opacity dark:text-[#000]">
                                Commander
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Carte 2 : Repassage -->
                <div class="rounded-3xl bg-white dark:bg-[#16211D] shadow-sm border border-gray-100 flex flex-col justify-between overflow-hidden transform transition hover:-translate-y-1 hover:shadow-md group">
                    <!-- Image d'illustration du service -->
                    <div class="h-48 overflow-hidden relative">
                        <img src=" {{ asset('images/repassage.jpg') }}" alt="Repassage" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    </div>

                    <div class="p-8 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-display text-xl font-bold text-[var(--color-text)] mb-3">Repassage</h3>
                            <p class="font-[family-name:var(--font-sans)] text-sm text-text leading-relaxed mb-6">
                                Repassage vapeur professionnel de haute précision qui préserve la fibre et l'éclat de vos tissus.
                            </p>
                        </div>
                        <div>
                            <div class="mb-6">
                                <span class="text-xs text-[var(--color-muted)] block uppercase tracking-wider">Tarif</span>
                                <span class="font-display text-2xl font-bold text-[var(--color-accent)]">dès 500 FCFA</span>
                                <span class="text-xs text-[var(--color-muted)]">/ pièce</span>
                            </div>
                            <a href="{{ route('services') }}" class="w-full inline-flex items-center justify-center font-[family-name:var(--font-sans)] font-medium text-sm bg-[var(--color-accent)] text-white py-3 px-4 rounded-xl shadow-sm hover:opacity-95 transition-opacity dark:text-[#000]">
                                Commander
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Carte 3 : Collecte & livraison -->
                <div class="rounded-3xl bg-white dark:bg-[#16211D] shadow-sm border border-gray-100 flex flex-col justify-between overflow-hidden transform transition hover:-translate-y-1 hover:shadow-md group relative">
                    <div class="absolute top-4 right-4 z-10 bg-[var(--color-accent)] text-white text-[10px] font-bold uppercase px-3 py-1 rounded-full tracking-wider shadow-md">
                        Option
                    </div>

                    <!-- Image d'illustration du service -->
                    <div class="h-48 overflow-hidden relative">
                        <img src=" {{ asset('images/collecte-livraison.jpg') }}" alt="Collecte et livraison" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    </div>

                    <div class="p-8 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-display text-xl font-bold text-[var(--color-text)] mb-3">Collecte & livraison</h3>
                            <p class="font-[family-name:var(--font-sans)] text-sm text-text leading-relaxed mb-6">
                                Option à ajouter à toute commande : nous venons récupérer votre linge à domicile et vous le rapportons.
                            </p>
                        </div>
                        <div>
                            <div class="mb-6">
                                <span class="text-xs text-[var(--color-muted)] block uppercase tracking-wider">Tarif</span>
                                <span class="font-display text-2xl font-bold text-[var(--color-accent)]">dès 1 000 FCFA</span>
                            </div>
                            <a href="{{ route('services') }}" class="w-full inline-flex items-center justify-center font-[family-name:var(--font-sans)] font-medium text-sm bg-[var(--color-accent)] text-white py-3 px-4 rounded-xl shadow-sm hover:opacity-95 transition-opacity dark:text-[#000]">
                                Ajouter l'option
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!---banner-->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-accent text-inverse relative overflow-hidden">
        <div class="max-w-4xl mx-auto text-center">

            <!-- Surtitre discret -->
            <span class="text-xs font-semibold tracking-widest uppercase text-inverse/80 mb-3 block">Service pressing sur mesure</span>

            <!-- Titre fort -->
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-semibold tracking-tight mb-6 text-inverse">
                Offrez à vos vêtements le soin qu'ils méritent.
            </h2>

            <!-- Description engageante -->
            <p class="text-inverse/90 text-base sm:text-lg max-w-2xl mx-auto mb-10 leading-relaxed">
                Testez notre simulateur de prix en quelques clics ou planifiez dès maintenant une collecte à domicile à Cotonou. Notre équipe s'occupe du reste.
            </p>

            <!-- Boutons d'action harmonisés (inversés pour contraster avec le fond accent) -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">

                <!-- Bouton Principal -->
                <x-button href="#simulateur" class="w-full sm:w-auto justify-center bg-bg text-text hover:bg-bg/90 hover:text-white py-3.5 px-8">
                    Lancer ma commande
                </x-button>

                <!-- Bouton Secondaire -->
                <x-button href="#contact" class="w-full sm:w-auto justify-center border border-inverse/40 text-inverse hover:border-inverse hover:bg-inverse/10 bg-transparent py-3.5 px-8">
                    Nous contacter directement
                </x-button>
            </div>

        </div>
    </section>
@endsection
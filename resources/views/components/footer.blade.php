<footer class="border-t border-muted/30 bg-bg text-text py-14">
    <div class="container mx-auto px-4 md:px-8">
        <div class="grid gap-10 pb-10 mb-10 md:grid-cols-[1.4fr_repeat(3,1fr)] max-[700px]:grid-cols-2">
            <div>
                 <x-pressing-logo />
                <p class="max-w-[260px] text-base sm:text-lg text-text">Le pressing en ligne pensé pour votre époque <br/> commandez, suivez, recevez.</p>
            </div>
            <div>
                <h4 class="mb-3.5 text-[0.78rem] font-semibold uppercase tracking-[0.06em] text-muted">Navigation</h4>
                <a class="block hover:cursor-pointer py-1 text-base sm:text-lg text-text opacity-85 transition hover:opacity-100 hover:text-accent" href="{{ route('home') }}">Accueil</a>
                <a class="block hover:cursor-pointer py-1 text-base sm:text-lg text-text opacity-85 transition hover:opacity-100 hover:text-accent" href="{{ route('client.orders.create') }}">Services</a>
                <a class="block hover:cursor-pointer py-1 text-base sm:text-lg text-text opacity-85 transition hover:opacity-100 hover:text-accent" href="{{ route('contact') }}">Contact</a>
            </div>
            <div>
                <h4 class="mb-3.5 text-[0.78rem] font-semibold uppercase tracking-[0.06em] text-muted">Compte</h4>
                <a class="block py-1 text-base sm:text-lg text-text opacity-85 transition hover:opacity-100 hover:text-accent hover:cursor-pointer" href="{{ route('login') }}">Se connecter</a>
                <a class="block py-1 text-base sm:text-lg text-text opacity-85 transition hover:opacity-100 hover:text-accent hover:cursor-pointer" href="{{ route('register') }}">S'inscrire</a>
            </div>
            <div>
                <h4 class="mb-3.5 text-[0.78rem] font-semibold uppercase tracking-[0.06em] text-muted">Paiement accepté</h4>
                <div class="flex flex-wrap gap-2.5 mt-1">
                    <span class="inline-block rounded-sm border border-muted px-2 py-1 text-[0.80em]  font-mono text-muted">Mobile Money</span>
                    <span class="inline-block rounded-sm border border-muted px-2 py-1 text-[0.80em]  font-mono text-muted">Carte</span>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-2 border-t border-muted/30 pt-6 text-[0.88rem] text-muted">
            <span>© 2026 Presso — Cotonou, Bénin</span>
            <span>Confidentialité · Conditions d'utilisation</span>
        </div>
    </div>
</footer>
@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
    <!-- En-tête de page moderne -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between pb-6 mb-8 border-b border-muted/10">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="font-display font-bold text-xl md:text-2xl text-text tracking-tight">Tableau de bord</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] md:text-xs font-mono bg-accent/10 text-accent border border-accent/20">
                    Live
                </span>
            </div>
            <p class="text-muted text-sm mt-1">Pilotez l'activité de votre pressing en temps réel.</p>
        </div>
    </div>

    <!-- SECTION 1 : ACTIVITÉ DE CE MOIS-CI (KPIs Principaux) -->
    <div class="mb-6">
        <h2 class="text-base md:text-lg font-mono uppercase tracking-wider text-muted mb-3 flex items-center gap-2">
            <i class="fa-solid fa-calendar-days text-accent"></i> Activité de ce mois-ci
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            
            <!-- Commandes du mois -->
            <div class="bg-content rounded-2xl p-6 border border-muted/10 shadow-sm hover:border-accent2/40 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-accent2/5 rounded-full blur-2xl group-hover:bg-accent2/10 transition-all pointer-events-none"></div>
                <div class="flex items-center justify-between relative z-10">
                    <span class="text-muted text-xs uppercase tracking-wider font-mono font-medium">Commandes (Mois)</span>
                    <div class="w-11 h-11 rounded-xl bg-inverse/5 flex items-center justify-center text-accent2 group-hover:scale-110 transition-transform shadow-inner">
                        <i class="fa-solid fa-calendar-check text-lg"></i>
                    </div>
                </div>
                <div class="mt-4 relative z-10">
                    <p class="font-display font-extrabold text-3xl text-text tracking-tight">{{ $stats['orders_this_month'] }}</p>
                    <div class="flex items-center gap-1.5 mt-2 text-xs text-muted">
                        <span class="text-accent2 font-medium font-mono">Période en cours</span>
                    </div>
                </div>
            </div>

            <!-- Chiffre d'affaires du mois -->
            <div class="bg-content rounded-2xl p-6 border border-muted/10 shadow-sm hover:border-accent/40 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-accent/5 rounded-full blur-2xl group-hover:bg-accent/10 transition-all pointer-events-none"></div>
                <div class="flex items-center justify-between relative z-10">
                    <span class="text-muted text-xs uppercase tracking-wider font-mono font-medium">Revenu mensuel</span>
                    <div class="w-11 h-11 rounded-xl bg-accent/10 flex items-center justify-center text-accent group-hover:scale-110 transition-transform shadow-inner">
                        <i class="fa-solid fa-wallet text-lg"></i>
                    </div>
                </div>
                <div class="mt-4 relative z-10">
                    <p class="font-display font-extrabold text-2xl lg:text-3xl text-accent tracking-tight truncate">
                        {{ number_format($stats['revenue_this_month'], 0, ',', ' ') }} <span class="text-sm font-sans font-normal text-muted">FCFA</span>
                    </p>
                    <div class="flex items-center gap-1.5 mt-2 text-xs text-muted">
                        <span class="text-accent font-medium font-mono">Encaissé ce mois</span>
                    </div>
                </div>
            </div>

            <!-- Nouveaux clients du mois -->
            <div class="bg-content rounded-2xl p-6 border border-muted/10 shadow-sm hover:border-muted/30 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-inverse/5 rounded-full blur-2xl group-hover:bg-inverse/10 transition-all pointer-events-none"></div>
                <div class="flex items-center justify-between relative z-10">
                    <span class="text-muted text-xs uppercase tracking-wider font-mono font-medium">Clients (Mois)</span>
                    <div class="w-11 h-11 rounded-xl bg-inverse/5 flex items-center justify-center text-text group-hover:scale-110 transition-transform shadow-inner">
                        <i class="fa-solid fa-user-plus text-lg"></i>
                    </div>
                </div>
                <div class="mt-4 relative z-10">
                    <p class="font-display font-extrabold text-3xl text-text tracking-tight">{{ $stats['clients_this_month'] }}</p>
                    <div class="flex items-center gap-1.5 mt-2 text-xs text-muted">
                        <span>Inscriptions récentes</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- SECTION 2 : STATISTIQUES GLOBALES -->
    <div class="mb-8">
        <h2 class="text-base md:text-lg font-mono uppercase tracking-wider text-muted mb-3 flex items-center gap-2">
            <i class="fa-solid fa-chart-line text-muted"></i> Vue d'ensemble globale
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Total Commandes Global -->
            <div class="bg-content rounded-2xl p-5 border border-muted/10 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-muted text-xs uppercase tracking-wider font-mono">Total commandes</span>
                    <span class="p-2 rounded-xl bg-inverse/5 text-accent"><i class="fa-solid fa-bag-shopping text-sm"></i></span>
                </div>
                <p class="font-display font-bold text-2xl text-text mt-2 font-mono">{{ $stats['total_orders'] }}</p>
                <span class="text-xs text-muted mt-1 block">Toutes périodes</span>
            </div>

            <!-- Revenu Global -->
            <div class="bg-content rounded-2xl p-5 border border-muted/10 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-muted text-xs uppercase tracking-wider font-mono">Revenu global</span>
                    <span class="p-2 rounded-xl bg-accent/10 text-accent"><i class="fa-solid fa-coins text-sm"></i></span>
                </div>
                <p class="font-display font-bold text-xl text-text mt-2 font-mono truncate">
                    {{ number_format($stats['globalRevenue'], 0, ',', ' ') }} <span class="text-xs font-normal">F</span>
                </p>
                <span class="text-xs text-muted mt-1 block">Cumul total encaissé</span>
            </div>

            <!-- Total Clients Global -->
            <div class="bg-content rounded-2xl p-5 border border-muted/10 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-muted text-xs uppercase tracking-wider font-mono">Total clients</span>
                    <span class="p-2 rounded-xl bg-inverse/5 text-muted"><i class="fa-solid fa-users text-sm"></i></span>
                </div>
                <p class="font-display font-bold text-2xl text-text mt-2 font-mono">{{ $stats['total_clients'] }}</p>
                <span class="text-xs text-muted mt-1 block">Base de clients active</span>
            </div>

            <!-- Total Agents -->
            <div class="bg-content rounded-2xl p-5 border border-muted/10 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-muted text-xs uppercase tracking-wider font-mono">Agents équipe</span>
                    <span class="p-2 rounded-xl bg-inverse/5 text-muted"><i class="fa-solid fa-user-shield text-sm"></i></span>
                </div>
                <p class="font-display font-bold text-2xl text-text mt-2 font-mono">{{ $stats['total_agents'] }}</p>
                <span class="text-xs text-muted mt-1 block">Personnel habilité</span>
            </div>

        </div>
    </div>

    <!-- Section Principale (Tableau & Widgets) -->
    <div class="grid lg:grid-cols-3 gap-6">
        
        <!-- Dernières commandes (Prend 2 colonnes) -->
        <div class="lg:col-span-2 bg-content rounded-2xl border border-muted/10 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="font-display font-bold text-base md:text-lg text-text">Dernières commandes</h2>
                        <p class="text-[11px] md:text-xs text-muted mt-0.5">Flux d'activité récent enregistré sur la plateforme</p>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-accent hover:underline flex items-center gap-1.5 bg-accent/5 px-3 py-1.5 rounded-lg transition-colors">
                        Tout voir <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse ($recentOrders as $order)
                        <div class="flex items-center justify-between p-3.5 bg-inverse/[0.02] hover:bg-inverse/[0.05] border border-muted/5 rounded-xl transition-all group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-accent/10 text-accent flex items-center justify-center font-mono text-sm font-bold shadow-inner group-hover:scale-105 transition-transform">
                                    <i class="fa-solid fa-shirt"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="font-mono text-sm font-bold text-text tracking-wide">{{ $order->tracking_code }}</p>
                                    </div>
                                    <p class="text-xs text-muted flex items-center gap-1.5 mt-1">
                                        <i class="fa-regular fa-user text-[10px] text-accent"></i> 
                                        <span class="font-medium text-text/80">{{ $order->user->name ?? 'Client inconnu' }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <x-status-badge :statut="$order->status" />
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 bg-inverse/[0.01] rounded-2xl border border-dashed border-muted/15">
                            <div class="w-14 h-14 rounded-full bg-muted/10 text-muted flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-box-open text-2xl"></i>
                            </div>
                            <p class="text-text font-medium text-sm">Aucune commande pour le moment</p>
                            <p class="text-muted text-xs mt-1">Les nouvelles commandes apparaîtront ici instantanément.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            @if($recentOrders->isNotEmpty())
                <div class="mt-6 pt-4 border-t border-muted/10 text-center">
                    <a href="{{ route('admin.orders.index') }}" class="text-xs text-muted hover:text-text font-medium transition-colors">
                        Afficher l'historique complet des commandes de l'atelier
                    </a>
                </div>
            @endif
        </div>

        <!-- Panneau Latéral (Statuts & Top Service) -->
        <div class="space-y-6">
            
            <!-- Répartition par statut -->
            <div class="bg-content rounded-2xl border border-muted/10 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-display font-bold text-base md:text-lg text-text">Répartition par statut</h2>
                    <i class="fa-solid fa-chart-pie text-muted text-sm"></i>
                </div>
                <div class="space-y-2.5">
                    @forelse ($ordersByStatus as $statut => $count)
                        <div class="flex items-center justify-between text-xs md:text-sm p-2 rounded-xl hover:bg-inverse/5 transition-colors">
                            <x-status-badge :statut="$statut" />
                            <span class="font-mono font-bold text-text bg-inverse/5 px-2.5 py-1 rounded-lg text-xs">{{ $count }}</span>
                        </div>
                    @empty
                        <div class="text-center py-6 text-muted text-xs md:text-sm">
                            Aucune statistique de statut.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Service le plus demandé (Carte VIP design avec accentuation) -->
            @if ($topService && $topService->service)
                <div class="bg-gradient-to-br from-accent/15 via-content to-content rounded-2xl border border-accent/30 p-6 shadow-sm relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 text-accent/10 text-8xl pointer-events-none">
                        <i class="fa-solid fa-award"></i>
                    </div>
                    <div class="flex items-center justify-between mb-3 relative z-10">
                        <div class="flex items-center gap-2 text-accent text-xs font-mono uppercase tracking-wider font-bold">
                            <i class="fa-solid fa-fire-flame-curved"></i> Best-seller
                        </div>
                        <span class="w-2 h-2 rounded-full bg-accent animate-ping"></span>
                    </div>
                    <p class="text-muted text-[11px] md:text-xs font-mono">Service plébiscité</p>
                    <p class="font-display font-bold text-sm md:text-base text-text mt-1 tracking-tight relative z-10">{{ $topService->service->name }}</p>
                    
                    <div class="mt-5 pt-3 border-t border-muted/10 flex items-center justify-between text-xs text-muted font-mono relative z-10">
                        <span>Volumes associés :</span>
                        <span class="font-bold text-text bg-accent/10 text-accent px-2 py-0.5 rounded-md">{{ $topService->total }} commandes</span>
                    </div>
                </div>
            @endif

        </div>

    </div>
@endsection
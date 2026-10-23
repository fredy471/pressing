<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;


// Imports pour l'espace Client
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Client\PaymentController as ClientPaymentController;
use App\Http\Controllers\PageController;

//imports pour l'espace admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PriceController as AdminPriceController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\ClothesTypeController as AdminClothesTypeController;

//imports pour l'espace agent
use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\Agent\OrderController as AgentOrderController;





// ==========================================
// PAGES PUBLIQUES
// ==========================================
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'sendContactForm'])->name('contact.form');

// Création de commande & API tarifs (accessible aux invités)
Route::get('/commande', [ClientOrderController::class, 'create'])->name('client.orders.create');
Route::post('/commande', [ClientOrderController::class, 'store'])->name('client.orders.store');
Route::get('/api/services/{service}/prices', [ClientOrderController::class, 'clothesTypesForService'])->name('client.orders.clothes-types-for-service');

// Callbacks & Webhooks Paiement
Route::get('/commandes/{reference}/paiement-callback', [ClientPaymentController::class, 'callback'])->name('client.payments.callback');
Route::post('/webhooks/fedapay', [ClientPaymentController::class, 'webhook'])->name('webhooks.fedapay');


// ==========================================
// ESPACE CLIENT AUTHENTIFIÉ
// ==========================================
Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {

    // 1. Routes statiques/spécifiques EN PREMIER (évite les conflits avec {order})
    Route::get('/commandes/reprise', [ClientOrderController::class, 'resume'])->name('orders.resume');
    Route::get('/commandes/validation', [ClientOrderController::class, 'pending'])->name('orders.pending');

    // 2. Historique des commandes
    Route::get('/mes-commandes', [ClientOrderController::class, 'index'])->name('orders.index');

    // 3. Route dynamique avec paramètre {order} EN DERNIER
    Route::get('/commandes/{order}', [ClientOrderController::class, 'show'])->name('orders.show');

    // 4. Paiements client
    Route::get('/payments/{reference}/status', [ClientPaymentController::class, 'status'])->name('payments.status');
    Route::post('/commandes/{reference}/payer', [ClientPaymentController::class, 'initiate'])->name('payments.initiate');
});

// Routes de gestion du profil (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ==========================================
// 2. ROUTE AGENT
// ==========================================
Route::middleware(['auth', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [AgentOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AgentOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AgentOrderController::class, 'updateStatus'])->name('orders.update-status');
});



// ==========================================
// 3. ESPACE ADMIN
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('services', AdminServiceController::class)->except(['destroy']);
    Route::patch('services/{service}/toggle-active', [AdminServiceController::class, 'toggleActive'])->name('services.toggle-active');
    Route::patch('/services/{service}/description', [AdminServiceController::class, 'updateDescription'])
        ->name('services.update-description');

    Route::resource('clothes-types', AdminClothesTypeController::class)->except(['destroy', 'update', 'show']);
    Route::patch('clothes-types/{clothesType}/toggle-active', [AdminClothesTypeController::class, 'toggleActive'])->name('clothes-types.toggle-active');
    Route::patch('clothes-types/{clothesType}/update', [AdminClothesTypeController::class, 'updateCategory'])->name('clothes-types.update-category');

    Route::resource('users', AdminUserController::class);

    Route::get('/prices', [AdminPriceController::class, 'index'])->name('prices.index');
    Route::patch('/prices', [AdminPriceController::class, 'update'])->name('prices.update');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
});

// Inclusion des routes d'authentification (Breeze)
require __DIR__ . '/auth.php';

<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;


// Imports pour l'espace Client
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Client\PaymentController as ClientPaymentController;

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
// 0. ROUTES PUBLIQUES


// Page d'accueil publique 
Route::get('/', [App\Http\Controllers\PageController::class, 'index'])->name('home');
Route::get('/contact', [App\Http\Controllers\PageController::class, 'contact'])->name('contact');
Route::post('/contact', [App\Http\Controllers\Client\ContactController::class, 'StoreMessage'])->name('contact.form');

Route::get('/commander', [ClientOrderController::class, 'create'])->name('orders.create');
Route::get('/api/services/{service}/prices', [ClientOrderController::class, 'clothesTypesForService']);
Route::get('/commander/{service}/{vetements}', [ClientOrderController::class, 'clothesTypesForService'])->name('orders.clothes-types');
Route::post('/commander', [ClientOrderController::class, 'store'])->name('orders.store');

Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});





// ==========================================
// 1. ROUTE CLIENT
// ==========================================

Route::post('/webhooks/fedapay', [ClientPaymentController::class, 'webhook'])->name('webhooks.fedapay');
Route::get('/commandes/{order}/paiement-callback', [ClientPaymentController::class, 'callback'])->name('client.payments.callback');

Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/mes-commandes', [ClientOrderController::class, 'index'])->name('orders.index');
    Route::get('/commander/{order}', [ClientOrderController::class, 'show'])->name('orders.show');
    Route::get('/commandes/{order}/payer', [ClientPaymentController::class, 'initiate'])->name('payments.initiate');
     Route::get('/payments/{reference}/status', [ClientPaymentController::class, 'status'])->name('payments.status');
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

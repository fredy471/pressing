<?php

use Illuminate\Support\Facades\Route;


// Imports pour l'espace Client
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\ProfileController as ClientProfileController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\PageController;

//imports pour l'espace admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PriceController as AdminPriceController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\ClothesTypeController as AdminClothesTypeController;


// ==========================================
// 0. ROUTES PUBLIQUES


// Page d'accueil publique 
Route::get('/', [App\Http\Controllers\PageController::class, 'index'])->name('home');
Route::get('/services', [App\Http\Controllers\PageController::class, 'services'])->name('services');
Route::get('/contact', [App\Http\Controllers\PageController::class, 'contact'])->name('contact');


Route::get('/commander', [ClientOrderController::class, 'create'])->name('orders.create');
Route::get('/commander/{service}/{vetements}', [ClientOrderController::class, 'clothesTypesForService'])->name('orders.clothes-types');
Route::post('/commander', [ClientOrderController::class, 'store'])->name('orders.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ClientProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ClientProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ClientProfileController::class, 'destroy'])->name('profile.destroy');
});



// ==========================================
// 1. ESPACE CLIENT
// ==========================================


Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/mes-commandes', [ClientOrderController::class, 'index'])->name('orders.index');
    Route::get('/commander/{order}', [ClientOrderController::class, 'show'])->name('orders.show');
});

// ==========================================
// 2. ESPACE AGENT
// ==========================================
Route::middleware(['auth', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', function () {
        return view('agent.dashboard');
    })->name('dashboard');
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

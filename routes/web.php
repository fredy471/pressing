<?php

use Illuminate\Support\Facades\Route;


// Page d'accueil publique 
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

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
use App\Http\Controllers\Admin\ClothesTypeController as AdminClothesPriceController;



// ==========================================
// 1. ESPACE CLIENT
// ==========================================
Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');

    // Gestion du profil
    Route::get('/profile', [ClientProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ClientProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ClientProfileController::class, 'destroy'])->name('profile.destroy');

    // Gestion des commandes
    Route::get('/orders', [ClientOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [ClientOrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [ClientOrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [ClientOrderController::class, 'show'])->name('orders.show');
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
});

// Inclusion des routes d'authentification (Breeze)
require __DIR__ . '/auth.php';

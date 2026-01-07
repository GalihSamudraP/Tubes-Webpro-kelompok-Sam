<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BaristaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/menu/{product}', [HomeController::class, 'show'])->name('menu.show');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Client Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // Ratings
    Route::post('/rating/product', [App\Http\Controllers\RatingController::class, 'storeProduct'])->name('rating.product');
    Route::post('/rating/barista', [App\Http\Controllers\RatingController::class, 'storeBarista'])->name('rating.barista');
});

// Barista Routes
Route::middleware(['auth'])->prefix('barista')->name('barista.')->group(function () {
    Route::get('/', [BaristaController::class, 'index'])->name('dashboard');
    Route::patch('/orders/{order}/status', [BaristaController::class, 'updateStatus'])->name('orders.updateStatus');
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Menu Management
    Route::get('/menu', [AdminController::class, 'menuIndex'])->name('menu.index');
    Route::post('/menu', [AdminController::class, 'menuStore'])->name('menu.store');
    Route::delete('/menu/{product}', [AdminController::class, 'menuDestroy'])->name('menu.destroy');

    // Promo Management
    Route::get('/promos', [AdminController::class, 'promoIndex'])->name('promos.index');
    Route::post('/promos', [AdminController::class, 'promoStore'])->name('promos.store');

    // User Management
    Route::get('/users', [AdminController::class, 'userIndex'])->name('users.index');
});

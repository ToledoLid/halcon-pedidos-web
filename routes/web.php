<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

// Rutas públicas
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación con API
Route::get('/login', [ApiAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [ApiAuthController::class, 'login'])->name('login.api');
Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout.api');

// Rutas protegidas (requieren token de API)
Route::middleware(['api.auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas de pedidos
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
    Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
    Route::post('/orders/{id}/photo', [OrderController::class, 'uploadPhoto'])->name('orders.photo');
    
    // Rutas de usuarios
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});
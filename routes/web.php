<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InventoryController;  // ← Agrega esta línea

// Rutas públicas
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/track', [HomeController::class, 'trackForm'])->name('track.form');
Route::post('/track', [HomeController::class, 'track'])->name('track');

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Gestión de usuarios (solo admin)
    Route::resource('users', UserController::class)->except(['show']);
    Route::patch('users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
    
    // Gestión de pedidos
    Route::resource('orders', OrderController::class);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('orders/{order}/photo', [OrderController::class, 'uploadPhoto'])->name('orders.upload-photo');
    Route::get('orders/trashed/list', [OrderController::class, 'trashed'])->name('orders.trashed');
    Route::patch('orders/{id}/restore', [OrderController::class, 'restore'])->name('orders.restore');
    Route::delete('orders/{id}/force-delete', [OrderController::class, 'forceDelete'])->name('orders.force-delete');
    
    // ========== NUEVAS RUTAS PARA ARCHIVADO ==========
    Route::patch('orders/{order}/archive', [OrderController::class, 'archive'])->name('orders.archive');
    Route::get('orders/archived/list', [OrderController::class, 'archived'])->name('orders.archived');
    Route::patch('orders/{id}/restore-archived', [OrderController::class, 'restoreArchived'])->name('orders.restore-archived');
    
    // ========== RUTAS PARA INVENTARIO ==========
    Route::resource('inventory', InventoryController::class);
    Route::post('inventory/{product}/adjust-stock', [InventoryController::class, 'adjustStock'])->name('inventory.adjust-stock');
});

require __DIR__.'/auth.php';
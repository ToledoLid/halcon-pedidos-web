<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Ruta de prueba para verificar que la API funciona
Route::get('/ping', function () {
    return response()->json(['message' => 'pong', 'status' => 'API funcionando']);
});

// Rutas públicas de API (no requieren autenticación)
// Usamos 'api-login' en lugar de 'login' para evitar conflicto con la ruta web
Route::post('/api-login', [AuthController::class, 'login'])->name('api.login');
Route::post('/api-register', [AuthController::class, 'register'])->name('api.register');
Route::get('/track/{invoice_number}', [OrderController::class, 'track'])->name('api.track');

// Rutas protegidas de API (requieren token)
Route::middleware('auth:sanctum')->group(function () {
    // Usuario actual
    Route::get('/user', [AuthController::class, 'user'])->name('api.user');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    
    // Órdenes
    Route::get('/orders', [OrderController::class, 'index'])->name('api.orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('api.orders.show');
    Route::post('/orders', [OrderController::class, 'store'])->name('api.orders.store');
    Route::put('/orders/{id}', [OrderController::class, 'update'])->name('api.orders.update');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('api.orders.destroy');
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('api.orders.update-status');
    Route::post('/orders/{id}/photo', [OrderController::class, 'uploadPhoto'])->name('api.orders.upload-photo');
});
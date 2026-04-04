<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Ruta raíz → redirige al dashboard si está autenticado, si no al login
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// Rutas de autenticación generadas por Breeze
require __DIR__.'/auth.php';

// ─── Rutas protegidas (requieren autenticación) ────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard — redirige internamente según el rol
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // ── Productos (Admin y Empleado) ──────────────────────────────────────
    Route::middleware(['role:admin|empleado'])->group(function () {//Las rutas para la gestión de productos están protegidas por el middleware 'role:admin|empleado', lo que significa que solo los usuarios con el rol de 'admin' o 'empleado' pueden acceder a estas rutas. Dentro de este grupo, se define un recurso para los productos utilizando 'Route::resource', pero se excluye la acción 'destroy' para que solo los administradores puedan eliminar productos.
        Route::resource('products', ProductController::class)
            ->except(['destroy']);   // destroy solo para admin
    });

    // ── Eliminar producto (solo Admin) ────────────────────────────────────
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->name('products.destroy')
        ->middleware('role:admin');

    // ── Gestión de usuarios (solo Admin) ─────────────────────────────────
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    });
});
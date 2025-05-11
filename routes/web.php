<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProductManagementController;

Route::get('/', [ProductManagementController::class, 'index'])->name('home');
Route::get('/products/{product}', [ProductManagementController::class, 'show'])->name('products.show');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');


// routes/web.php
Route::middleware(['auth:web', 'verified'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/update', [CartController::class, 'update']);
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

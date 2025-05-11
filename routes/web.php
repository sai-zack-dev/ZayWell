<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProductManagementController;

Route::get('/', [ProductManagementController::class, 'index'])->name('home');
Route::get('/products/{product}', [ProductManagementController::class, 'show'])->name('products.show');
Route::post('/cart', [ProductManagementController::class, 'store'])->name('cart.store');


Route::get('/cart', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth:web', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

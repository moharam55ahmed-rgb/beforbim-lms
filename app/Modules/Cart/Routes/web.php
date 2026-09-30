<?php

use App\Modules\Cart\Controllers\CartController;
use Illuminate\Support\Facades\Route;

// Cart Web Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::match(['GET', 'POST'], '/cart/add/{course}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');

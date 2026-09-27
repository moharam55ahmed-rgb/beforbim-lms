<?php

use App\Modules\Order\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// Order Web Routes
Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

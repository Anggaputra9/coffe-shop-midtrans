<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShopController::class, 'index'])->name('shop.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/orders/{uuid}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/api/orders/{uuid}/status', [OrderController::class, 'status'])->name('orders.status');
Route::get('/riwayat', [OrderController::class, 'history'])->name('orders.history');
Route::post('/riwayat', [OrderController::class, 'lookup'])->name('orders.history.lookup');
Route::post('/riwayat/lupa', [OrderController::class, 'forgetEmail'])->name('orders.history.forget');

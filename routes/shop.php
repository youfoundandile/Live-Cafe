<?php

use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\OrderController;
use App\Http\Controllers\Shop\PaymentController;
use App\Http\Controllers\Shop\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Shop Routes
|--------------------------------------------------------------------------
| Public product browsing is available to all visitors.
| Cart, checkout, and orders require authentication.
|--------------------------------------------------------------------------
*/

Route::prefix('shop')->name('shop.')->group(function () {

    // 1. Public Routes — Product browsing (no login required)
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');

    // 2. Authenticated & Verified Customers Only
    // Everything in this group is blocked until the user signs up and clicks their email link
    Route::middleware(['auth', 'verified'])->group(function () {

        // Cart Management
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
        Route::patch('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
        Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

        // Checkout Processing
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

        // Order History
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::delete('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

        // Payment
        Route::get('/payment/start', [PaymentController::class, 'initialize'])->name('payment.start');
        Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
        Route::get('/payment/cancelled', [PaymentController::class, 'cancelled'])->name('payment.cancelled');
    });
});

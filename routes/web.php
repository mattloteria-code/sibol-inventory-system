<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;

Route::get('/', function () {
    return redirect()->route('products.index');
});

Route::resource('categories', CategoryController::class);
Route::resource('products', ProductController::class);
Route::resource('customers', CustomerController::class);

Route::prefix('orders/cart')->name('orders.cart.')->group(function () {
    Route::get('/', [OrderController::class, 'cart'])->name('index');
    Route::post('/customer', [OrderController::class, 'setCustomer'])->name('set-customer');
    Route::post('/add/{product}', [OrderController::class, 'addToCart'])->name('add');
    Route::patch('/update/{product}', [OrderController::class, 'updateCartItem'])->name('update');
    Route::delete('/remove/{product}', [OrderController::class, 'removeFromCart'])->name('remove');
    Route::post('/clear', [OrderController::class, 'clearCart'])->name('clear');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
});

Route::resource('orders', OrderController::class);
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

Route::prefix('inventory')->name('inventory.')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('index');
    Route::get('/restock', [InventoryController::class, 'restockForm'])->name('restock.form');
    Route::post('/restock', [InventoryController::class, 'restock'])->name('restock');
    Route::get('/movements', [InventoryController::class, 'movements'])->name('movements');
    Route::get('/low-stock', [InventoryController::class, 'lowStock'])->name('low-stock');
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuditLogController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::resource('categories', CategoryController::class);
Route::resource('products', ProductController::class);
Route::resource('customers', CustomerController::class);
Route::resource('expenses', ExpenseController::class);
Route::resource('expense-categories', ExpenseCategoryController::class);

Route::prefix('orders/cart')->name('orders.cart.')->group(function () {
    Route::get('/', [OrderController::class, 'cart'])->name('index');
    Route::post('/customer', [OrderController::class, 'setCustomer'])->name('set-customer');
    Route::post('/add/{product}', [OrderController::class, 'addToCart'])->name('add');
    Route::patch('/update/{product}', [OrderController::class, 'updateCartItem'])->name('update');
    Route::delete('/remove/{product}', [OrderController::class, 'removeFromCart'])->name('remove');
    Route::post('/clear', [OrderController::class, 'clearCart'])->name('clear');
    Route::post('/payment-method', [OrderController::class, 'setPaymentMethod'])->name('payment-method');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
});

Route::patch('/orders/{order}/mark-paid', [OrderController::class, 'markAsPaid'])->name('orders.mark-paid');

Route::resource('orders', OrderController::class);
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

Route::prefix('inventory')->name('inventory.')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('index');
    Route::get('/restock', [InventoryController::class, 'restockForm'])->name('restock.form');
    Route::post('/restock', [InventoryController::class, 'restock'])->name('restock');
    Route::get('/movements', [InventoryController::class, 'movements'])->name('movements');
    Route::get('/low-stock', [InventoryController::class, 'lowStock'])->name('low-stock');
});

Route::prefix('ingredients/purchase')->name('ingredients.purchase.')->group(function (){
    Route::get('/', [IngredientController::class, 'purchaseForm'])->name('form');
    Route::post('/', [IngredientController::class, 'storePurchase'])->name('store');
});

Route::resource('ingredients', IngredientController::class);

Route::prefix('product/{product}/recipe')->name('products.recipe.')->group(function () {
    Route::get('/', [RecipeController::class, 'edit'])->name('edit');
    Route::post('/', [RecipeController::class, 'store'])->name('store');
    Route::patch('/{productIngredient}', [RecipeController::class, 'update'])->name('update');
    Route::delete('/{productIngredient}', [RecipeController::class, 'destroy'])->name('destroy');
});

Route::get('/production', [ProductionController::class, 'index'])->name('production.index');
Route::get('/production/create', [ProductionController::class, 'selectProduct'])->name('production.create');
Route::get('/production/create/{product}', [ProductionController::class, 'create'])->name('production.create.form');
Route::post('/production', [ProductionController::class, 'store'])->name('production.store');
Route::get('/production/{production}', [ProductionController::class, 'show'])->name('production.show');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('reports')->name('reports.')->group( function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
    Route::get('/expenses', [ReportController::class, 'expenses'])->name('expenses');
    Route::get('/profit-loss', [ReportController::class, 'profitLoss'])->name('profit-loss');
    Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
    Route::get('/customers', [ReportController::class, 'customers'])->name('customers');
});

Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');


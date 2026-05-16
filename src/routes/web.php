<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;

Route::get('/', [StoreController::class, 'index'])->name('store.index');
Route::get('/checkout/{product:slug}', [StoreController::class, 'checkout'])->name('store.checkout');
Route::post('/checkout', [StoreController::class, 'placeOrder'])->name('store.place-order');

Route::get('/order/{orderId}', [OrderController::class, 'status'])->name('order.status');
Route::post('/order/{orderId}/pin', [OrderController::class, 'verifyPin'])->name('order.verify-pin');
Route::get('/order/{orderId}/confirmation', [OrderController::class, 'confirmation'])->name('order.confirmation');
Route::get('/order/{orderId}/refresh', [OrderController::class, 'refresh'])->name('order.refresh');
Route::get('/order/{orderId}/download', [OrderController::class, 'download'])->name('order.download');

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::post('/order/{order}/deliver', [AdminController::class, 'deliver'])->name('admin.order.deliver');
    Route::post('/order/{order}/mark-paid', [AdminController::class, 'markPaid'])->name('admin.order.mark-paid');
});
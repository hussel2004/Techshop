<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPromotionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Home and Products (Public Routes)
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Super Deals (Promotions)
Route::get('/super-deals', [PromotionController::class, 'index'])->name('promotions.index');

// Shopping Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/orders', [CheckoutController::class, 'orders'])->name('orders.index');

    // Breeze Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes (Requires auth + admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', AdminProductController::class);
    Route::delete('/products/images/{image}', [AdminProductController::class, 'deleteImage'])->name('products.images.delete');
    Route::post('/products/images/{image}/set-primary', [AdminProductController::class, 'setPrimaryImage'])->name('products.images.set-primary');
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('orders', AdminOrderController::class);
    Route::resource('promotions', AdminPromotionController::class);
});

// Breeze Authentication Routes
require __DIR__.'/auth.php';

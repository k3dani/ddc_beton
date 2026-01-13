<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\LocationController as PublicLocationController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\LocationProductPriceController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

// Publikus oldalak
Route::get('/', [PageController::class, 'homepage'])->name('homepage');
Route::get('/ecoblock', [PageController::class, 'ecoblock'])->name('ecoblock');
Route::get('/ecocrete', [PageController::class, 'ecocrete'])->name('ecocrete');
Route::get('/magunkrol', [PageController::class, 'about'])->name('about');
Route::get('/minoseg', [PageController::class, 'quality'])->name('quality');
Route::get('/szallitasi-feltetelek', [PageController::class, 'shipping'])->name('shipping');
Route::get('/tanacsok', [PageController::class, 'tips'])->name('tips');
Route::get('/ujdonsagok', [PageController::class, 'news'])->name('news');

// Shop
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/{category}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/termek/{slug}', [ShopController::class, 'show'])->name('shop.product');
Route::post('/kosar/{slug}', [ShopController::class, 'addToCart'])->name('cart.add');

// Telephely választás
Route::get('/location/{slug}', [PublicLocationController::class, 'select'])->name('location.select');
Route::post('/location/clear', [PublicLocationController::class, 'clear'])->name('location.clear');

// Admin routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Locations CRUD
    Route::resource('locations', LocationController::class);
    
    // Products CRUD
    Route::resource('products', AdminProductController::class);
    
    // Product Categories CRUD
    Route::resource('categories', ProductCategoryController::class);
    
    // Telephely-Termék-Ár mátrix
    Route::get('/location-product-prices', [LocationProductPriceController::class, 'index'])->name('location-product-prices.index');
    Route::put('/location-product-prices', [LocationProductPriceController::class, 'update'])->name('location-product-prices.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

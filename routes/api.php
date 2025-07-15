<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/shop/register', [ShopController::class,"ShopRegister"])->name('shopRegister');
Route::post('/admin/register', [UserController::class,"adminRegister"])->name('adminRegister');
Route::post('/user/register', [UserController::class,"userRegister"])->name('userRegister');
Route::post('/user/login', [LoginController::class,"userLogin"])->name('userLogin');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, "Index"])->name('dashboard');

    Route::get('/analysis/sale/{year}', [SaleController::class, "availableYears"])->name('availableYears');
    Route::get('/analysis/sale/graph/{year}', [SaleController::class, "saleGraph"])->name('saleGraph');
    Route::get('/analysis/sale/details/{year}', [SaleController::class, "salesDetails"])->name('salesDetails');
    Route::get('/analysis/sale/revenue/{year}', [SaleController::class, "revenue"])->name('revenue');

    Route::get('/revenue', [ShopController::class, "totalRevenues"])->name('totalRevenues');
    Route::post('/revenue/target', [ShopController::class, "revenueTarget"])->name('Target');

    Route::get('/analysis/product/{years}', [ProductController::class, 'availableYears'])->name('availableYears');
    Route::get('/analysis/product/graph/{year}', [ProductController::class, "productGraph"])->name('productGraph');
    
    Route::get('shop/product/category', [ProductController::class, "productCategory"])->name('productCategory');

    Route::get('/shop/product', [ProductController::class, "productDetails"])->name('productDetails');
    Route::get('/shop/product/search', [ProductController::class, "searchProducts"])->name('searchProducts');
    Route::post('/shop/product/new', [ProductController::class, "addProduct"])->name('addProduct');

    Route::post('/shop/inventory/update', [InventoryController::class, "updateStock"])->name('updateStock');
});

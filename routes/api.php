<?php

use App\Http\Controllers\Api\DashboardController;
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
    Route::get('/analysis/sale/monthly/{year}', [SaleController::class, "graphData"])->name('graphData');
    Route::get('/analysis/sale/details/{year}', [SaleController::class, "salesDetails"])->name('salesDetails');
    Route::get('/analysis/sale/revenue/{year}', [SaleController::class, "revenue"])->name('revenue');

    Route::get('/analysis/product/{years}', [ProductController::class, 'availableYears'])->name('availableYears');
});

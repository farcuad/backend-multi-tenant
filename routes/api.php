<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\SalesHistoryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LowStockController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PasswordResetController;


Route::post('/register', [AuthController::class, 'register'] );
Route::post('/login', [AuthController::class, 'login'] );

Route::post('password/email', [PasswordResetController::class, 'sendResetLink']);
Route::post('password/reset', [PasswordResetController::class, 'reset'])->name('password.reset');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/products',ProductController::class);
    Route::post('/sales', [SaleController::class, 'store']);
    Route::get('/sales-history', [SalesHistoryController::class, 'index']);
    Route::get('/low-stock', [LowStockController::class, 'index']);
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
});

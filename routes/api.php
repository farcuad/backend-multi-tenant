<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\SalesHistoryController;
use App\Http\Controllers\Api\AuthController;
Route::post('/register', [AuthController::class, 'register'] );
Route::post('/login', [AuthController::class, 'login'] );

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/products',ProductController::class);
    Route::post('/sales', [SaleController::class, 'store']);
    Route::get('/sales-history', [SalesHistoryController::class, 'index']);
});

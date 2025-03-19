<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    //Category Routes
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'show']);
    Route::get('/categories/edit/{id}', [CategoryController::class,  'edit']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class,  'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    //Products Routes
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/products/edit/{id}', [ProductController::class,  'edit']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class,  'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    
    //Orders Routes
    Route::post('/orders', [OrderController::class, 'store']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
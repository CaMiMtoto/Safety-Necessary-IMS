<?php

use App\Http\Controllers\Api\CustomersController;
use App\Http\Controllers\Api\SuppliersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/suppliers', [SuppliersController::class, 'index']);
    Route::get('/suppliers/{supplier}', [SuppliersController::class, 'show']);
    Route::post('/suppliers', [SuppliersController::class, 'store']);
    Route::put('/suppliers/{supplier}', [SuppliersController::class, 'update']);
    Route::delete('/suppliers/{supplier}', [SuppliersController::class, 'destroy']);

    Route::get('/customers', [CustomersController::class, 'index']);
    Route::get('/customers/{customer}', [CustomersController::class, 'show']);
    Route::post('/customers', [CustomersController::class, 'store']);
    Route::put('/customers/{customer}', [CustomersController::class, 'update']);
    Route::delete('/customers/{customer}', [CustomersController::class, 'destroy']);
});


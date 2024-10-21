<?php

use App\Http\Controllers\Admin\TokoController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Sql\QueryController;
use Illuminate\Support\Facades\Route;

Route::post('/user/register', [AuthController::class, 'register']);
Route::post('/user/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/user', [AuthController::class, 'show']);
    Route::post('/user/logout', [AuthController::class, 'logout']);

    Route::post('/sql-query', [QueryController::class, 'data']);
    Route::post('/sql-query-toko', [QueryController::class, 'queryToko']);
    Route::get('/toko', [TokoController::class, 'data']);
});

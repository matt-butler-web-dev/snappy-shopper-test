<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;

Route::get('/shop', [ShopController::class, 'index']);
Route::get('/shop/{id}', [ShopController::class, 'show']);
Route::post('/shop', [ShopController::class, 'store']);
Route::delete('/shop/{id}', [ShopController::class, 'destroy']);
Route::post('/shop/localShops', [ShopController::class, 'localShops']);
Route::post('/shop/availableLocalShops', [ShopController::class, 'availableLocalShops']);




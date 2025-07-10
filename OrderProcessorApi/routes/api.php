<?php

use Illuminate\Support\Facades\Route;

Route::get('/orders', [App\Http\Controllers\Orders\ListOrderController::class, 'handle']);
Route::get('/products', [App\Http\Controllers\Products\ListProductController::class, 'handle']);

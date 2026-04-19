<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;

Route::get('/services/{id}/fields', [OrderController::class, 'getServiceFields']);
Route::middleware('auth')->post('/orders', [OrderController::class, 'store']);

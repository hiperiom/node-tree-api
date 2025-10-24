<?php

use App\Http\Middleware\SetLocaleMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NodeController;

Route::get('/nodes/index',[NodeController::class, 'index']);

Route::post('/nodes/store',[NodeController::class, 'store'])
->middleware(SetLocaleMiddleware::class);

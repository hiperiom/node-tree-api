<?php

use App\Http\Middleware\SetLocaleMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NodeController;

Route::get('/nodes',[NodeController::class, 'index']);

Route::post('/nodes',[NodeController::class, 'store'])
->middleware(SetLocaleMiddleware::class);

Route::delete('/nodes/{id}', [NodeController::class, 'destroy']);

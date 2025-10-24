<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NodeController;

Route::post('/nodes/store',[NodeController::class, 'store']);

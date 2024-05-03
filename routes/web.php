<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WaterMeterController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/readings', [WaterMeterController::class, 'getReadings']);

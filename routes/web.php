<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WaterMeterController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/display', function () {
    return view('display');
});

Route::get('/readings', [WaterMeterController::class, 'getReadings']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/store-reading', [WaterMeterController::class, 'storeReading']);

Route::post('/process-payment', [WaterMeterController::class, 'processPayment']);
// routes/web.php

Route::post('/topup', [WaterMeterController::class, 'topup'])->name('topup');

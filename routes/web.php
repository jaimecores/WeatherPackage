<?php

use Illuminate\Support\Facades\Route;
use JaimeCores\WeatherPackage\Http\Controllers\ForecastController;

Route::get('/forecast', [ForecastController::class, 'index'])->name('forecast.index');
Route::post('/forecast', [ForecastController::class, 'store'])->name('forecast.store');

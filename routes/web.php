<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\SkydropTrackingController;

use Botble\Ecommerce\Http\Controllers\Customers\PublicController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/ruta-prueba',[TrackingController::class, 'quotation'])->name('ruta.prueba');

Route::get('/customer/tracking-zensara',[TrackingController::class,'tracking'])->name('');
Route::get('/customer/tracking',[PublicController::class,'GetTracking'])->name('customer.tracking');
Route::post('/customer/quotation/admin',[TrackingController::class,'quotationAdmin'])->name('quotation.admin');
Route::post('/customer/rate/admin',[TrackingController::class,'processRateAdmin'])->name('tracking.admin');

Route::get('/generate-order', [SkydropTrackingController::class, 'generateOrder']);

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get('stripe', [StripeController::class, 'index']);
Route::post('payment-process', [StripeController::class, 'process'])->name('payment.process');


Route::get('pagos',  [StripeController::class, 'pagos']);
Route::post('payStripe',  [StripeController::class, 'payStripe'])->name("payStripe");
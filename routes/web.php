<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CartController;

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

Route::group([
    'prefix' => 'cart'
], function () {
    Route::post('add', [CartController::class, 'add'])->name('cart.add');
    Route::get('get', [CartController::class, 'get'])->name('cart.get');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Auth;

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
    if (Auth::check()) {
        return redirect('/pay');
    }

    return view('home');
});

Route::get('/register', [RegisterController::class, 'index']);
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'logIn']);

Route::any('/logout', [LoginController::class, 'logOut'])->middleware('auth');

Route::get('/pay', [PaymentController::class, 'index'])->middleware('auth');
Route::post('/pay', [PaymentController::class, 'pay'])->middleware('auth');

Route::get('/pay/{id}', [PaymentController::class, 'index'])->middleware('auth');

Route::get('/pay/{id}/result', [PaymentController::class, 'result'])->middleware('auth');

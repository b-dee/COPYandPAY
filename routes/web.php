<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return Auth::check() ? redirect('/pay') : view('home');
});

Route::get('/register', [RegisterController::class, 'index']);
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'logIn']);

Route::any('/logout', [LoginController::class, 'logOut'])->middleware('auth');

Route::get('/pay', [PaymentController::class, 'index'])->middleware('auth');
Route::post('/pay', [PaymentController::class, 'pay'])->middleware('auth');
Route::get('/pay/history', [PaymentController::class, 'history'])->middleware('auth');
Route::get('/pay/{id}', [PaymentController::class, 'index'])->middleware('auth');
Route::get('/pay/{id}/result', [PaymentController::class, 'result'])->middleware('auth');

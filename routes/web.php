<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Rutas para login con facebook
Route::get('login/facebook', [App\Http\Controllers\Auth\FacebookLonginController::class, 'login'])
    ->name('login.facebook');
//Route::get('login/facebook/callback', [App\Http\Controllers\Auth\FacebookLonginController::class, 'callback']);
Route::get('facebook/auth/callback', [App\Http\Controllers\Auth\FacebookLonginController::class, 'loginWithFacebook']);

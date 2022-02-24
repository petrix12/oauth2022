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
Route::get('facebook/auth/callback', [App\Http\Controllers\Auth\FacebookLonginController::class, 'loginWithFacebook']);

// Rutas para login con twitter
Route::get('login/twitter', [App\Http\Controllers\Auth\TwitterLonginController::class, 'login'])
    ->name('login.twitter');
Route::get('twitter/auth/callback', [App\Http\Controllers\Auth\TwitterLonginController::class, 'loginWithTwitter']);

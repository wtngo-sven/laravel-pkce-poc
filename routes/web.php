<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/login',  [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// PKCE redirect + callback
Route::get('/redirect', [OAuthController::class, 'redirect'])->middleware('auth')->name('oauth.redirect');
Route::get('/callback', [OAuthController::class, 'callback'])->name('oauth.callback');


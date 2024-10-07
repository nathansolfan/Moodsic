<?php

use App\Http\Controllers\SpotifyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [SpotifyController::class, 'login'])->name('spotify.login');
Route::get('/callback', [SpotifyController::class, 'callback'])->name('spotify.callback');

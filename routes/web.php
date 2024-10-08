<?php

use App\Http\Controllers\SpotifyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [SpotifyController::class, 'login'])->name('spotify.login');
Route::get('/callback', [SpotifyController::class, 'callback'])->name('spotify.callback');

Route::get('mood', function () {
return view('mood');
})->name('mood.select');

Route::post('/playlist', [SpotifyController::class, 'generatePlaylist'])->name('playlist.generate');

Route::get('/webplayback', function () {
    return view('webplayback');
})->name('webplayback');

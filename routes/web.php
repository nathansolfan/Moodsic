<?php

use App\Http\Controllers\SpotifyController;
use App\Http\Controllers\WebPlaybackController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    session()->flash('error', 'Test error message');
    return view('welcome');
});



// Existing SpotifyController routes

Route::get('/login', [SpotifyController::class, 'login'])->name('spotify.login');
Route::get('/callback', [SpotifyController::class, 'callback'])->name('spotify.callback');

Route::get('mood', function () {
return view('mood');
})->name('mood.select');

Route::post('/playlist', [SpotifyController::class, 'generatePlaylist'])->name('playlist.generate');


// New WebPlaybackController routes
Route::get('/webplayback/login', [WebPlaybackController::class, 'login'])->name('webplayback.login');
Route::get('/webplayback/callback', [WebPlaybackController::class, 'callback'])->name('webplayback.callback');
Route::get('/webplayback', [WebPlaybackController::class, 'playback'])->name('webplayback');


// LiveWire Playback
Route::get('/livewireplayback' , function () {
    return view('livewireplayback');
});

// React Route
Route::get('reactspotify', function() {
    return view('reactspotify');
});

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpotifyController extends Controller
{
    public function login()
    {
        $clientId = env('SPOTIFY_CLIENT_ID');
        $redirectUri = env('SPOTIFY_REDIRECT_URI');
        // Scopes: Spotify users using third-party apps that only the information they choose to share will be shared
        $scopes = 'user-read-private user-read-email playlist-read-private playlist-modify-public';

        return redirect("https://accounts.spotify.com/authorize?client_id={$clientId}&response_type=code&redirect_uri={$redirectUri}&scope={$scopes}");
    }
}

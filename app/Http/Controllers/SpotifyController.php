<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
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

    public function callback(Request $request)
    {
        $code = $request->input('code');

        // Exchange authorization code for access token
        $client = new Client();
        $response = $client->post('https://accounts.spotify.com/api/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => env('SPOTIFY_REDIRECT_URI'),
                'client_id' => env('SPOTIFY_CLIENT_ID'),
                'client_secret' => env('SPOTIFY_CLIENT_SECRET')
            ],
        ]);

        $body = json_decode($response->getBody());

        // store access token in sesion for future requests
        session(['spotify_access_token' => $body->access_token]);

        return redirect()->route('mood.select');
    }
}

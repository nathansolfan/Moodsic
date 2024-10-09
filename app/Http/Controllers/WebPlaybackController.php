<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class WebPlaybackController extends Controller
{
    public function login()
    {
        $clientId = env('SPOTIFY_CLIENT_ID');
        $redirectUri = env('SPOTIFY_REDIRECT_URI');
        $scopes = 'user-read-private user-read-email streaming user-read-playback-state user-modify-playback-state';

        return redirect("https://accounts.spotify.com/authorize?client_id={$clientId}&response_type=code&redirect_uri={$redirectUri}&scope={$scopes}");
    }

    // Callback method after Spotify authentication
    public function callback(Request $request)
    {
        $code = $request->input('code');

        // Exchange auth code for access token
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

        // Store access token in session
        session(['spotify_access_token' => $body->access_token]);

        // Redirect to Web Plaback view
        return redirect()->route('webplayback');
    }

    // Render Web Playback view
    public function playback()
    {
        return view('webplayback');
    }



}

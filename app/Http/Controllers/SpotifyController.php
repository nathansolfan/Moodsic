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

    public function generatePlaylist(Request $request)
    {
        $mood = $request->input('mood');
        $seedGenres = $this->getMoodGenres($mood);

        // Get access token from session
        $accessToken = session('spotify_access_token');

        // Make request to API
        $client = new Client();
        $response = $client->get('https://api.spotify.com/v1/recommendations', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken, // Ensure there is a space between 'Bearer' and the token
            ],
            'query' => [
                'seed_genres' => implode(',', $seedGenres),
                'limit' => 10,
                'target_energy' => $this->getEnergyLevel($mood),
                'target_valence' => $this->getValence($mood),
            ],
        ]);

        $body = json_decode($response->getBody());

        // Pass the tracks to the view
        return view('playlist', ['tracks' => $body->tracks]);

    }

    private function getMoodGenres($mood)
    {
        switch ($mood) {
            case 'happy':
                return ['pop', 'dance'];
            case 'sad':
                return ['acoustic', 'blues'];
            case 'energetic':
                return ['rock', 'edm'];
            case 'relaxed':
                return ['chill', 'ambient'];
            default:
                return ['pop'];
        }
    }

    private function getEnergyLevel($mood)
    {
        switch ($mood) {
            case 'energetic':
                return 0.9;
            case 'happy':
                return 0.7;
            case 'sad':
                return 0.2;
            case 'relaxed':
                return 0.4;
            default:
                return 0.5;
        }
    }

    private function getValence($mood)
    {
        switch ($mood) {
            case 'energetic':
                return 0.9;
            case 'happy':
                return 0.7;
            case 'sad':
                return 0.1;
            case 'relaxed':
                return 0.5;
            default:
                return 0.3;
        }
    }

}

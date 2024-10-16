<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ReactWebPlaybackController extends Controller
{
    // Example: Method to start playing a track
    public function playTrack(Request $request)
    {
        $deviceId = $request->input('device_id');
        $token = session('spotify_webplayback_token');

        if (!$token) {
            return response()->json(['error' => 'No Spotify token found in session'], 403);
        }

        try {
            $client = new Client();
            $response = $client->request('PUT', 'https://api.spotify.com/v1/me/player/play?device_id=' . $deviceId, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
            ]);

            if ($response->getStatusCode() == 204) {
                return response()->json(['message' => 'Track started playing']);
            } else {
                return response()->json(['error' => 'Failed to play track'], $response->getStatusCode());
            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Add other necessary methods here
}

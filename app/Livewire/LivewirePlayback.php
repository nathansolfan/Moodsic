<?php

namespace App\Livewire;

use GuzzleHttp\Client;
use Livewire\Component;

class LivewirePlayback extends Component
{
    public $trackName = 'Loading...';
    public $artistName = 'Loading...';
    public $isPlaying = false;
    public $errorMessage = '';
    public $albumArt = '';
    public $progress = '00:00';
    public $duration = '00:00';
    public $deviceId = '';

    // Declare the token property
    protected $token;

    public function mount()
{
    // Try to fetch the access token
    $this->token = session('spotify_webplayback_token');  // Using the same session key for both
    // dd($this->token);
    if (!$this->token) {

        $this->errorMessage = 'No access token available. Please log in.';
    } else {
        $this->loadTrackInfo();
    }
}

public function togglePlay()
{
    // Fetch the token again to ensure session persistence
    $token = session('spotify_webplayback_token');

    if (!$token) {
        $this->errorMessage = 'No access token available.';
        return;
    }

    try {
        $client = new Client();

        // Check for active devices
        $deviceResponse = $client->get('https://api.spotify.com/v1/me/player/devices', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        $devices = json_decode($deviceResponse->getBody());

        if (empty($devices->devices)) {
            $this->errorMessage = 'No active devices found. Please ensure a Spotify player is active.';
            return;
        }

        // Use the first active device or specify a device ID from the list
        $device_id = $devices->devices[0]->id;

        // Make sure to transfer playback to this device first
        $client->put('https://api.spotify.com/v1/me/player', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'json' => [
                'device_ids' => [$device_id],  // Forcefully transfer playback to this device
                'play' => true,  // Start playing immediately
            ],
        ]);

        // Now toggle the play/pause state
        $url = $this->isPlaying
            ? 'https://api.spotify.com/v1/me/player/pause'
            : 'https://api.spotify.com/v1/me/player/play';

        // Send the command to Spotify
        $client->put($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'json' => [
                'device_id' => $device_id,  // Explicitly pass the device_id
            ],
        ]);

        // Update the playing state
        $this->isPlaying = !$this->isPlaying;

    } catch (\Exception $e) {
        $this->errorMessage = 'Error toggling play: ' . $e->getMessage();
    }
}



public function refreshTrackInfo()
{
    $this->loadTrackInfo();
}


    public function loadTrackInfo()
    {
        $token = session('spotify_webplayback_token');

        if (!$token) {
            $this->errorMessage = 'No access token available';
            return;
        }

        try {
            $client = new Client();
            $response = $client->get('https://api.spotify.com/v1/me/player/currently-playing', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);

            $body = json_decode($response->getBody());

            if ($body && $body->item) {
                $track = $body->item;
                $this->trackName = $track->name;
                $this->artistName = implode(', ', array_map(fn($artist) => $artist->name, $track->artists));
                $this->albumArt = $track->album->images[0]->url;
                $this->duration = gmdate("i:s", $track->duration_ms / 1000);
                $this->progress = gmdate("i:s", $body->progress_ms / 1000);
            }
        } catch (\Exception $e) {
            $this->errorMessage = 'Error fetching track info: ' . $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.livewire-playback');
    }
}

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

    protected $token;

    public function mount()
    {
        // Fetch access token from session
        $this->token = session('spotify_webplayback_token');

        if (!$this->token) {
            $this->errorMessage = 'No access token available. Please log in.';
        } else {
            $this->loadTrackInfo();
        }
    }

    public function togglePlay()
    {
        if (!$this->token) {
            $this->errorMessage = 'No access token available.';
            return;
        }

        try {
            $client = new Client();
            // Determine play or pause based on current state
            $url = $this->isPlaying
                ? 'https://api.spotify.com/v1/me/player/pause'
                : 'https://api.spotify.com/v1/me/player/play';

            $client->put($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                ],
            ]);
            $this->isPlaying = !$this->isPlaying;
        } catch (\Exception $e) {
            $this->errorMessage = 'Error toggling play: ' . $e->getMessage();
        }
    }

    public function loadTrackInfo()
    {
        if (!$this->token) {
            $this->errorMessage = 'No access token available';
            return;
        }

        try {
            $client = new Client();
            $response = $client->get('https://api.spotify.com/v1/me/player/currently-playing', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
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

    // Optional: Refresh track info every few seconds
    public function refreshTrackInfo()
    {
        $this->loadTrackInfo();
    }
}

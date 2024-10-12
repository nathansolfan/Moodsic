<?php

namespace App\Livewire;

use GuzzleHttp\Client;
use Livewire\Component;

class LivewirePlayback extends Component
{
    public $trackname = 'Loading...';
    public $artistName = 'Loading...';
    public $isPlaying = false;
    public $errorMessage = '';
    public $albumArt = '';
    public $progress = '00:00';
    public $duration = '00:00';

    protected $token;

    public function mount()
    {
        // fetch access token from session
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

        // Call SPOTIFY API to toggle play/pause
        $client = new Client();
        $response = $client->put('https://api.spotify.com/v1/me/player/play', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
            ],
        ]);
        $this->isPlaying = !$this->isPlaying;
    }

    public function loadTrackInfo()
    {

    }




    public function render()
    {
        return view('livewire.livewire-playback');
    }
}

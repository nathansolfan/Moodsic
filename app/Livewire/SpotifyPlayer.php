<?php

namespace App\Livewire;

use Livewire\Component;

class SpotifyPlayer extends Component
{

    public $trackName;
    public $artistName;
    public $isPlaying = false;




    public function render()
    {
        return view('livewire.spotify-player');
        // state and playback logic in PHP.
    }
}

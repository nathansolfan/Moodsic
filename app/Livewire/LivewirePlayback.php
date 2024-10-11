<?php

namespace App\Livewire;

use Livewire\Component;

class LivewirePlayback extends Component
{
    public $trackname = 'Loading...';
    public $artistName = 'Loading...';
    public $isPlaying = false;
    public $errorMessage = '';

    public function togglePlay()
    {

    }

    public function loadTrackInfo()
    {

    }

    public function mount()
    {

    }


    public function render()
    {
        return view('livewire.livewire-playback');
    }
}

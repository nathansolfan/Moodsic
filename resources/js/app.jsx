import React from 'react';
import './bootstrap';
import { createRoot } from 'react-dom/client';
import SpotifyPlayer from './components/SpotifyPlayer';

const spotifyElement = document.getElementById('spotify-player');
if (spotifyElement) {
    const root = createRoot(spotifyElement);
    root.render(<SpotifyPlayer />);

}

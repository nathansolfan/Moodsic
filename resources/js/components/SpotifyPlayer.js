import { useEffect, useState } from "react";

const SpotifyPlayer = () => {
    const [trackName, setTrackName] = useState('Loading...');
    const [artistName, setArtistName] = useState('Loading...');
    const [albumArt, setAlbumArt] = useState('');
    const [isPlaying, setIsPlaying] = useState(false);
    const [progress, setProgress] = useState('00:00');
    const [duration, setDuration] = useState('00:00');
    const [player, setPlayer] = useState(null);
    const [deviceId, setDeviceId] = useState(null);
    const [errorMessage, setErrorMessage] = useState('');
}

// Helper function to format time in mm:ss format

const formatTime = (milliseconds) => {
    const minutes = Math.floor(milliseconds / 60000);
    const seconds = Math.floor((milliseconds % 60000) / 1000).toFixed(0)
    return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
}

useEffect( () => {
    const token = document.querySelector('meta[name="spotify-token"]').getAttribute('content');

    // Initialize Player
    window.onSpotifyWebPlaybackSDKReady = () => {
        const playerInstance = new window.Spotify.Player({
            name: 'Web Playback SDK',
            getOAuthToken: cb => { cb(token); },
            volume: 0.5
        })

        setPlayer(playerInstance);

        // Error handling
        playerInstance.addListener('initialization_error', ({ message }) => setErrorMessage(message));
        playerInstance.addListener('authentication_error', ({ message}) => setErrorMessage(message));
        playerInstance.addListener('account_error', ({ message }) => setErrorMessage(message));
        playerInstance.addListener('playback_error', ({ message }) => setErrorMessage(message));

        // Ready Event
        playerInstance.addListener('ready', ({ device_id }) => {
            console.log('Ready with Device ID', device_id);
            setDeviceId(device_id);
        })

        // Not Ready Event
        playerInstance.addListener('not_ready', ({ device_id}) => {
            console.log('Device ID has gone offline', device_id);
        });

        // Playback state changed
        playerInstance.addListener('player_state_changed', (state) => {
            if (!state) return;

            const currentTrack = state.track_window.current_track;
            setTrackName(currentTrack.name);
        })










    }
})


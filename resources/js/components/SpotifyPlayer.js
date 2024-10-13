import { useEffect, useState } from "react";

const SpotifyPlayer = () => {
    const [trackName, setTrackName] = useState('Loading...');
    const [artistName, setArtistName] = useState('Loading...');
    const [albumArt, setAlbumArt] = useState('');
    const [isPlaying, setIsPlaying] = useState(false);
    const [progress, setProgress] = useState('00:00');
    const [duration, setDuration] = useState('00:00');
}

useEffect( () => {
    const token = document.querySelector('meta[name="spotify-token"]').getAttribute('content');
    const player = new window.Spotify.Player({
        name: 'Spotify Web Playback',
        getOAuthToken: cb => cb(token),
        volume: 0.5,
    });

    player.addListener('player_state_changed', state => {
        if (state) {
            setTrackName(state.track_window.current_track.name);
            setArtistName(state.track_window.current_track.artists.map(artist => artist.name).join(', '));
            setAlbumArt(state.track_window.current_track.album.images[0].url);
            setIsPlaying(!state.paused);
            setDuration(formatTime(state.track_window.current_track.duration_ms));
            setProgress(formatTime(state.position));

        }
    })




})

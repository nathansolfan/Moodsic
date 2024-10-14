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

    // Helper function to format time in mm:ss format
    const formatTime = (milliseconds) => {
        const minutes = Math.floor(milliseconds / 60000);
        const seconds = Math.floor((milliseconds % 60000) / 1000).toFixed(0);
        return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    };

    useEffect(() => {
        const token = document.querySelector('meta[name="spotify-token"]').getAttribute('content');

        // Initialize Player
        window.onSpotifyWebPlaybackSDKReady = () => {
            const playerInstance = new window.Spotify.Player({
                name: 'Web Playback SDK',
                getOAuthToken: cb => { cb(token); },
                volume: 0.5
            });

            setPlayer(playerInstance);

            // Error handling
            playerInstance.addListener('initialization_error', ({ message }) => setErrorMessage(message));
            playerInstance.addListener('authentication_error', ({ message }) => setErrorMessage(message));
            playerInstance.addListener('account_error', ({ message }) => setErrorMessage(message));
            playerInstance.addListener('playback_error', ({ message }) => setErrorMessage(message));

            // Ready Event
            playerInstance.addListener('ready', ({ device_id }) => {
                console.log('Ready with Device ID', device_id);
                setDeviceId(device_id);
            });

            // Not Ready Event
            playerInstance.addListener('not_ready', ({ device_id }) => {
                console.log('Device ID has gone offline', device_id);
            });

            // Playback state changed
            playerInstance.addListener('player_state_changed', (state) => {
                if (!state) return;

                const currentTrack = state.track_window.current_track;
                setTrackName(currentTrack.name);
                setArtistName(currentTrack.artists.map(artist => artist.name).join(', '));
                setAlbumArt(currentTrack.album.images[0].url);
                setDuration(formatTime(currentTrack.duration_ms));
                setProgress(formatTime(state.position));
                setIsPlaying(!state.paused);
            });

            // Connect to the player
            playerInstance.connect();
        };
    }, []);

    // Function toggle play/pause
    const togglePlay = () => {
        if (player) {
            player.togglePlay().catch(error => setErrorMessage('Failed to toggle play/pause.'));
        }
    };

    return (
        <div className="bg-gray-800 text-white rounded-lg p-6 max-w-lg w-full shadow-md">
            <h1 className="text-3xl font-bold text-center mb-8">Spotify Web Playback (React)</h1>

            {/* Display any error msg */}
            {errorMessage && (
                <div className="text-red-500 mb-4 text-center">{errorMessage}</div>
            )}

            {/* Track Info */}
            <div>
                {albumArt && (
                    <img src={albumArt} alt="Album Art" className="w-48 h-48 mx-auto rounded-lg shadow-lg" />
                )}
                <h2>{trackName}</h2>
                <p>{artistName}</p>
            </div>

            {/* Play Button */}
            <div>
                <button
                    onClick={togglePlay}
                    className="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-600 transition duration-200"
                >
                    {isPlaying ? 'Pause' : 'Play'}
                </button>
            </div>

            {/* Track Progress */}
            <div id="progress-container">
                <span id="current-progress">{progress}</span> / <span id="track-duration">{duration}</span>
                <div>
                    <div
                        id="progress-bar-fill"
                        className="bg-green-500 h-full"
                        style={{ width: `${(progress !== '00:00' && duration !== '00:00') ? (parseInt(progress.replace(':', '')) / parseInt(duration.replace(':', ''))) * 100 : 0}%` }}
                    ></div>
                </div>
            </div>

            {/* Device ID */}
            {deviceId && (
                <div>
                    Device ID: {deviceId}
                </div>
            )}
        </div>
    );
};

export default SpotifyPlayer;

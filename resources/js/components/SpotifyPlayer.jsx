import React, { useEffect, useState } from 'react';

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
        if (milliseconds == null) return '00:00';  // Handle null durations
        const minutes = Math.floor(milliseconds / 60000);
        const seconds = Math.floor((milliseconds % 60000) / 1000).toFixed(0);
        return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    };

    useEffect(() => {
        const token = document.querySelector('meta[name="spotify-token"]').getAttribute('content');
        console.log("Spotify token:", token);

        if (!window.Spotify) {
            console.log("Spotify SDK not loaded yet. Loading...");
            const script = document.createElement('script');
            script.src = "https://sdk.scdn.co/spotify-player.js";
            script.async = true;

            script.onload = () => {
                console.log('Spotify SDK loaded');
                initializePlayer(token);
            };

            script.onerror = () => {
                setErrorMessage('Spotify SDK failed to load');
                console.error('Spotify SDK failed to load');
            };

            document.body.appendChild(script);
        } else {
            console.log('Spotify SDK already loaded');
            initializePlayer(token);
        }
    }, []);

    const initializePlayer = (token) => {
        window.onSpotifyWebPlaybackSDKReady = () => {
            console.log("Spotify Web Playback SDK is ready");

            const playerInstance = new window.Spotify.Player({
                name: 'Web Playback SDK',
                getOAuthToken: cb => { cb(token); },
                volume: 0.5
            });

            setPlayer(playerInstance);

            // Error handling
            playerInstance.addListener('initialization_error', ({ message }) => {
                console.error("Initialization error:", message);
                setErrorMessage(message);
            });

            playerInstance.addListener('authentication_error', ({ message }) => {
                console.error("Authentication error:", message);
                setErrorMessage(message);
            });

            playerInstance.addListener('account_error', ({ message }) => {
                console.error("Account error:", message);
                setErrorMessage(message);
            });

            playerInstance.addListener('playback_error', ({ message }) => {
                console.error("Playback error:", message);
                setErrorMessage(message);
            });

            // Ready Event
            playerInstance.addListener('ready', ({ device_id }) => {
                console.log('Ready with Device ID:', device_id);
                setDeviceId(device_id);
            });

            // Not Ready Event
            playerInstance.addListener('not_ready', ({ device_id }) => {
                console.log('Device ID has gone offline:', device_id);
            });

            // Playback state changed
            playerInstance.addListener('player_state_changed', (state) => {
                console.log("Player state changed:", state);
                if (!state || !state.track_window || !state.track_window.current_track) {
                    console.log("No active track playing");
                    setTrackName('No active track');
                    setArtistName('');
                    setAlbumArt('');
                    return;
                }

                // Update track details
                const currentTrack = state.track_window.current_track;
                setTrackName(currentTrack.name);
                setArtistName(currentTrack.artists.map(artist => artist.name).join(', '));
                setAlbumArt(currentTrack.album.images[0]?.url || '');
                setDuration(formatTime(currentTrack.duration_ms));
                setProgress(formatTime(state.position));
                setIsPlaying(!state.paused);
            });

            // Connect to the player
            playerInstance.connect();
        };
    };

    // Update player state every second
    useEffect(() => {
        let intervalId;

        if (player) {
            console.log("Setting up interval to update player state...");
            intervalId = setInterval(() => {
                player.getCurrentState().then(state => {
                    if (state) {
                        console.log("Player state:", state);
                        setProgress(formatTime(state.position));
                    }
                }).catch(err => {
                    console.error("Error getting player state:", err);
                });
            }, 1000); // Update every second
        }

        return () => {
            console.log("Clearing interval to stop state updates.");
            clearInterval(intervalId);
        };
    }, [player]);

    // Function toggle play/pause
    const togglePlay = () => {
        if (player) {
            console.log("Toggling play/pause...");
            player.togglePlay().catch(error => {
                console.error("Failed to toggle play/pause:", error);
                setErrorMessage('Failed to toggle play/pause.');
            });
        } else {
            console.log("Player not available or ready yet.");
            setErrorMessage('Player is not available or ready yet.');
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
                </div>
            )}
        </div>
    );
};

export default SpotifyPlayer;

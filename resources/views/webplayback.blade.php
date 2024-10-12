<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Spotify Web Playback</title>
    @vite('resources/css/app.css')
    <script src="https://sdk.scdn.co/spotify-player.js"></script> <!-- Load the Spotify Web Playback SDK -->
    {{-- @vite('resources/js/audiofeatures.js') --}}
</head>
<body class="bg-gray-900 flex items-center justify-center h-screen">
    <div class="bg-gray-800 text-white rounded-lg p-6 max-w-lg w-full shadow-md">
        <h1 class="text-3xl font-bold text-center mb-8">Spotify Web Playback</h1>

        <!-- Display any error message from the session -->
        @if (session('error'))
            <div class="text-red-500 mb-4 text-center">{{ session('error') }}</div>
        @endif

        <!-- Loading Spinner-->
        <div id="loading" class="text-center mb-4 hidden">
            <svg class="animate-spin h-8 w-8 text-green-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <span class="sr-only">Loading...</span>
        </div>

        <!-- Current Track info -->
        <div class="text-center mb-6">
            <img id="album-art" class="w-48 h-48 mx-auto rounded-lg shadow-lg" src="" alt="Album Art">
            <h2 id="track-name" class="text-xl font-semibold mt-4"></h2>
            <p id="artist-name" class="text-gray-400"></p>
        </div>

        {{-- <!-- Audio Features -->
        <div class="text-center mb-6">
            <h3>Audio Features</h3>
            <p id="valence" class="text-gray-400"></p>
            <p id="energy" class="text-gray-400"></p>
            <p id="danceability" class="text-gray-400"></p>
            <p id="tempo" class="text-gray-400"></p>
            <p id="acousticness" class="text-gray-400"></p>
            <p id="instrumentalness" class="text-gray-400"></p>
        </div> --}}

        <!-- Button to toggle play/pause -->
        <div class="text-center mb-4">
            <button id="togglePlay" class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-600 transition duration-200">
                Toggle Play
            </button>
        </div>

        <!-- Track Progress-->
        <div id="progress-container" class="text-center mb-4">
            <span id="current-progress" class="text-sm">00:00</span> / <span id="track-duration" class="text-sm">00:00</span>
            <div class="bg-gray-600 mt-2 w-full h-2 rounded-full overflow-hidden">
                <div id="progress-bar-fill" class="bg-green-500 h-full" style="width: 0%;"></div>
            </div>
        </div>

        <!-- Display Spotify Player Events -->
        <p id="status" class="text-center text-sm text-gray-400 mt-4"></p>
    </div>

    <script>
        function displayError(message) {
            const statusElement = document.getElementById('status')
            statusElement.innerText = message;
            statusElement.classList.add('text-red-500');
        }

        // Function to show or hide the loading spinner
        function showLoading(show) {
            document.getElementById('loading').classList.toggle('hidden', !show);
        }

        // Function to format time in mm:ss format
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = Math.floor(seconds % 60);
            return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
        }

        window.onSpotifyWebPlaybackSDKReady = () => {
            showLoading(true); // Show loading spinner while initializing

            const token = '{{ session('spotify_webplayback_token') }}';
            const player = new Spotify.Player({
                name: 'My Web Playback Player',
                getOAuthToken: cb => { cb(token); },
                volume: 0.5
            });

            // Ready Event
            player.addListener('ready', ({ device_id }) => {
                console.log('Ready with Device ID', device_id);
                document.getElementById('status').innerText = 'Player is ready with Device ID ' + device_id;
                showLoading(false); // Hide loading spinner when ready
            });

            // Not Ready Event
            player.addListener('not_ready', ({ device_id }) => {
                console.log('Device ID has gone offline', device_id);
                document.getElementById('status').innerText = 'Device ID has gone offline ' + device_id;
                showLoading(false); // Hide loading spinner if player is not ready
            });

            // Error Listeners
            player.addListener('initialization_error', ({ message }) => {
                console.error(message);
                displayError('Initialization error: ' + message);
                showLoading(false); // Hide loading on error
            });

            player.addListener('authentication_error', ({ message }) => {
                console.error(message);
                displayError('Authentication error: ' + message);
                showLoading(false); // Hide loading spinner on error
            });

            player.addListener('account_error', ({ message }) => {
                console.error(message);
                displayError('Account error: ' + message);
                showLoading(false); // Hide loading spinner on error
            });

            // Toggle Play Button
            document.getElementById('togglePlay').onclick = function () {
                player.togglePlay();
            };

            // Function to update track info
            function updateTrackInfo(state) {
                const track = state.track_window.current_track;
                document.getElementById('track-name').textContent = track.name;
                document.getElementById('artist-name').textContent = track.artists.map(artist => artist.name).join(', ');
                document.getElementById('album-art').src = track.album.images[0].url;
                document.getElementById('track-duration').textContent = formatTime(track.duration_ms / 1000);
            }

            // Function to update progress bar
            function updateProgress(state) {
                const currentProgress = state.position / 1000;
                const totalDuration = state.duration / 1000;
                const progressPercentage = (currentProgress / totalDuration) * 100;

                document.getElementById('current-progress').textContent = formatTime(currentProgress);
                document.getElementById('progress-bar-fill').style.width = `${progressPercentage}%`;
            }

            // Connect Player and Start State Monitoring
            player.connect().then(success => {
                if (success) {
                    console.log('Player connected successfully!');
                    // Introduce a slight delay before starting to check player state
                    setTimeout(() => {
                        // Periodically check the player state after initialization
                        setInterval(() => {
                            player.getCurrentState().then(state => {
                                if (state && state.track_window && state.track_window.current_track) {
                                    updateTrackInfo(state);
                                    updateProgress(state);
                                    document.getElementById('status').innerText = '';
                                } else {
                                    console.log('No active track playing');
                                    document.getElementById('status').innerText = 'No active track playing';
                                }
                                showLoading(false); // Hide loading spinner when state is available
                            }).catch(error => {
                                console.error('Error getting player state', error);
                                displayError('Error getting player state');
                                showLoading(false); // Hide loading spinner on error
                            });
                        }, 1000); // Polling every 1 second
                    }, 2000); // Initial delay of 2 seconds
                } else {
                    console.error('Failed to connect to the player!');
                    displayError('Failed to connect to the player!');
                    showLoading(false);
                }
            });
        };
    </script>
</body>
</html>

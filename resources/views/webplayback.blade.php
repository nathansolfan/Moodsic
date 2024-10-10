<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Spotify Web Playback</title>
    @vite('resources/css/app.css')
    <script src="https://sdk.scdn.co/spotify-player.js"></script> <!-- Load the Spotify Web Playback SDK -->
</head>
<body class="bg-gray-900 flex items-center justify-center h-screen">
    <div class="bg-gray-800 text-white rounded-lg p-6 max-w-lg w-full shadow-md">
        <h1 class="text-3xl font-bold text-center mb-8">Spotify Web Playback</h1>

        <!-- Display any error message from the session -->
        @if (session('error'))
            <div class="text-red-500 mb-4 text-center">{{ session('error') }}</div>
        @endif

        <!-- Current Track info -->
        <div class="text-center mb-6">
            <img id="album-art" class="w-48 h-48 mx-auto rounded-lg shadow-lg" src="" alt="Album Art">
            <h2 id="track-name" class="text-xl font-semibold mt-4"></h2>
            <p id="artist-name" class="text-gray-400"></p>
        </div>

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
        window.onSpotifyWebPlaybackSDKReady = () => {
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
            });

            // Not Ready Event
            player.addListener('not_ready', ({ device_id }) => {
                console.log('Device ID has gone offline', device_id);
                document.getElementById('status').innerText = 'Device ID has gone offline ' + device_id;
            });

            // Error Listeners
            player.addListener('initialization_error', ({ message }) => console.error(message));
            player.addListener('authentication_error', ({ message }) => console.error(message));
            player.addListener('account_error', ({ message }) => console.error(message));

            // Toggle Play
            document.getElementById('togglePlay').onclick = function() {
                player.togglePlay();
            };

            // Function to format time in mm:ss format
            function formatTime(seconds) {
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = Math.floor(seconds % 60);
                return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
            }

            // Update track info
            function updateTrackInfo(state) {
                const track = state.track_window.current_track;
                document.getElementById('track-name').textContent = track.name;
                document.getElementById('artist-name').textContent = track.artists.map(artist => artist.name).join(', ');
                document.getElementById('album-art').src = track.album.images[0].url;
                document.getElementById('track-duration').textContent = formatTime(track.duration_ms / 1000);
            }

            // Update Progress
            function updateProgress(state) {
                const currentProgress = state.position / 1000;
                const totalDuration = state.duration / 1000;
                const progressPercentage = (currentProgress / totalDuration) * 100;

                document.getElementById('current-progress').textContent = formatTime(currentProgress);
                document.getElementById('progress-bar-fill').style.width = `${progressPercentage}%`;
            }

            // Periodically check the player state
            setInterval(() => {
                player.getCurrentState().then(state => {
                    if (state) {
                        updateTrackInfo(state);
                        updateProgress(state);
                    }
                });
            }, 1000);

            // Connect Player
            player.connect();
        };
    </script>
</body>
</html>

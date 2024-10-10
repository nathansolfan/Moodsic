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
<body>
    <div>
        <h1>Spotify Web Playback</h1>

        <!-- Display any error message from the session -->
        @if (session('error'))
            <div class="text-red-500 mb-4">{{ session('error') }}</div>
        @endif

        <!-- Current Track info -->
        <div>
            <img id="album-art" src="" alt="Album Art">
            <h2 id="track-name"></h2>
            <p id="artist-name"></p>
        </div>

        <!-- Button to toggle play/pause -->
        <button id="togglePlay">
            Toggle Play
        </button>

        <!-- Track Progress-->
        <div id="progress-container">
            <span id="current-progress">00:00</span> / <span id="track-duration">00:00</span>
            <div class="progress-bar bg-gray-200 mt-2 w-full h-1">
                <div id="progress-bar-fill" class="bg-green-500 h-full" style="width: 0%;"></div>
            </div>
        </div>


        <!-- Display Spotify Player Events -->
        <p id="status" class="text-center mt-4"></p>
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
            player.addListener('initialization_error', ({ message}) => console.error(message));
            player.addListener('authentication_error', ({ message}) => console.error(message));
            player.addListener('account_error', ({ message}) => console.error(message));

            // Toggle Play
            document.getElementById('togglePlay').onclick = function() {
                player.togglePlay();
            };

            // Connect Player
            player.connect()
        };
    </script>
</body>
</html>

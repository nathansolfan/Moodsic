<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Spotify Web Player (React)</title>
    @vite('resources/css/app.css')
    <meta name="spotify-token" content="{{ session('spotify_webplayback_token') }}">

    <!-- Load the Spotify Web Playback SDK -->
    <script src="https://sdk.scdn.co/spotify-player.js"></script>
</head>
<body>
    <div id="spotify-player"></div>

    @viteReactRefresh
    @vite('resources/js/app.jsx')
</body>
</html>

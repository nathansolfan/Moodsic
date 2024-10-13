<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Spotify Web Playback (Livewire)</title>
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body>
    <div>
        <h1 class="text-3xl font-bold text-center mb-8">Spotify Web Playback (Livewire)</h1>
        <!-- Embed the Livewire component -->
        <livewire:livewire-playback />
    </div>

    @livewireScripts

    {{-- Optional: Auto-refresh track info every 10 seconds --}}
    <script>
        setInterval(function() {
            Livewire.emit('refreshTrackInfo');
        }, 5000); // Refresh every 5 seconds
    </script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Spotify Web Playback (Livewire)</title>
    @vite('resource/css/app.css')
    @livewireStyles
</head>
<body>
    <div>
        <h1 class="text-3xl font-bold text-center mb-8">Spotify Web Playback (Livewire)</h1>
        <!-- Embed the Livewire component -->
        <livewire:livewire-playback />
    </div>

    <div class="bg-gray-800 text-white rounded-lg p-6 max-w-lg w-full shadow-md">
        <h1 class="text-3xl font-bold text-center mb-8">Spotify Web Playback (Livewire)</h1>

        {{-- Display errors --}}
        @if ($errorMessage)
        <div class="text-red-500 mb-4 text-center">{{ $errorMessage }}</div>
        @endif

        {{-- Track Ifno --}}
        <div class="text-center mb-6">
            <img src=" {{ $albumArt }} " alt="Album Art">
            <h2> {{ $trackName}} </h2>
            <p> {{ $artistName}} </p>
        </div>

        {{-- Play btn --}}
        <div class="text-center mb-4">
            <button wire:click="togglePlay" class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-600 transition duration-200">
                {{ $isPlaying ? 'Pause' : 'Play'}}
            </button>
        </div>

        {{-- Track progress --}}
        <div id="progress-container" class="text-center mb-4">
            <span id="current-progress" class="text-sm"> {{ $progress }} </span>
        </div>

    </div>

</body>
</html>

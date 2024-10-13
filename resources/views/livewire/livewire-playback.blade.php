<div class="bg-gray-800 text-white rounded-lg p-6 max-w-lg w-full shadow-md">
    <h1 class="text-3xl font-bold text-center mb-8">Spotify Web Playback (Livewire)</h1>

    {{-- Display errors --}}
    @if ($errorMessage)
        <div class="text-red-500 mb-4 text-center">{{ $errorMessage }}</div>
    @endif

    {{-- Track Info --}}
    <div class="text-center mb-6">
        @if ($albumArt)
            <img src="{{ $albumArt }}" alt="Album Art" class="w-48 h-48 mx-auto rounded-lg shadow-lg">
        @endif
        <h2 class="text-xl font-semibold mt-4">{{ $trackName }}</h2>
        <p class="text-gray-400">{{ $artistName }}</p>
    </div>

    {{-- Play Button --}}
    <div class="text-center mb-4">
        <button wire:click="togglePlay" class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-600 transition duration-200">
            {{ $isPlaying ? 'Pause' : 'Play' }}
        </button>
    </div>

    {{-- Track Progress --}}
    <div id="progress-container" class="text-center mb-4">
        <span id="current-progress" class="text-sm">{{ $progress }}</span> / <span id="track-duration" class="text-sm">{{ $duration }}</span>
        <div class="bg-gray-600 mt-2 w-full h-2 rounded-full overflow-hidden">
            <div id="progress-bar-fill" class="bg-green-500 h-full" style="width: {{ ($progress != '00:00' && $duration != '00:00') ? (intval(str_replace(':', '', $progress)) / intval(str_replace(':', '', $duration))) * 100 : 0 }}%;"></div>
        </div>
    </div>
</div>

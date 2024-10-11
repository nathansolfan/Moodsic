<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" />
</head>
<body class="font-sans antialiased bg-gray-50 text-black">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-2xl mx-auto text-center p-6">
            <!-- Link to Spotify Web Playback -->
            <h1 class="text-2xl font-bold mb-4">Welcome to the Spotify Web Playback App</h1>

            <!-- Link for localhost -->
            <a href="http://localhost:8000/webplayback/callback" class="text-lg text-blue-500 underline">
                Go to Spotify Web Playback (localhost)
            </a>

            <!-- Display session error message if available -->
            @if(session('error'))
                <div class="bg-red-500 text-white p-4 rounded-md mt-4">
                    {{ session('error') }}
                </div>
            @endif

            <footer class="mt-8 text-sm text-gray-500">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </footer>
        </div>
    </div>
</body>
</html>

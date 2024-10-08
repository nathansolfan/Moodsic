<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Your Playlist</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-4xl">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Your Playlist</h1>
        <ul class="space-y-4">
            @foreach ($tracks as $track)
            <li class="bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{$track->name}}</h2>
                        <p class="text-gray-600">by {{$track->artists[0]->name}}</p>
                    </div>
                    @if ($track->preview_url)
                    <audio controls class="ml-4">
                        <source src="{{$track->preview_url}}" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                    @else
                    <p class="text-red-500 ml-4">No preview available</p>
                    @endif
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</body>
</html>

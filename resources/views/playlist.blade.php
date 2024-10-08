<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Your Playlist</title>
    @vite('resources/css/app.css')

</head>
<body>
    <h1>Your Playlist</h1>
    <ul>
        @foreach ($tracks as $track)
        <li> {{$track->name}} by {{$track->artists[0]->name }}

        @if ($track->preview_url)
        <br>
        <audio controls>
            <source src=" {{$track->preview_url}}" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
        @else
        <p>No preview Available</p>
        @endif
    </li>
        @endforeach
    </ul>
</body>
</html>

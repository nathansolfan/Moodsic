<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Select Your Mood</title>
    @vite('resources/css/app.css')

</head>
<body>
    <h1>Select your Mood</h1>
    <form action=" {{route('playlist.generate')}}" method="POST" >
        @csrf
        <select name="mood">
            <option value="happy">Happy</option>
            <option value="sad">Sad</option>
            <option value="energetic">Energetic</option>
            <option value="relaxed">Relaxed</option>
        </select>
        <button type="submit">Generate Playlist</button>
    </form>

</body>
</html>

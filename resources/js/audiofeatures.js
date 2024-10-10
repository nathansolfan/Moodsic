// Fetch the audio features
function getAudioFeatures(trackId, token) {
    fetch(`https://api.spotify.com/v1/audio-features/${trackId}`, {
        headers: {
            'Authorization': `Bearer ${token}`
        }
    })
    .then(response => response.json())
    .then(data => {
        displayAudioFeatures(data); // Call a function to display the data
    })
    .catch(error => {
        console.log('Error fetching audio features', error)
    })
}

// Display the audio features in the UI
function displayAudioFeatures(features) {
    document.getElementById('valence').textContent = `Valence: ${features.valence}`;
    document.getElementById('energy').textContent = `Energy: ${features.energy}`;
    document.getElementById('danceability').textContent = `Danceability: ${features.danceability}`;
    document.getElementById('tempo').textContent = `Tempo: ${features.tempo} BPM`;
    document.getElementById('acousticness').textContent = `Acousticness: ${features.acousticness}`;
    document.getElementById('instrumentalness').textContent = `Instrumentalness: ${features.instrumentalness}`;
}

export { getAudioFeatures }; // Export the function so it can be used in Blade


// Update your UI


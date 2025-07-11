<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Personalized Playlist</title>
  <link rel="stylesheet" href="Playlist.css"> <!-- Link to your CSS file for styling -->
</head>
<body>
  <div class="playlist-container">
    <h2>Your Personalized Playlist</h2>
    <div class="content-section">
      <div class="input-section">
        <select id="category-select" class="category-select">
          <option value="none">Select a Category</option>
          <option value="workout">Workout</option>
          <option value="relaxation">Relaxation</option>
          <option value="motivation">Motivation</option>
        </select>
      </div>
      <div id="playlist" class="playlist">
        <div class="playlist-item">No recommendations available. Please select a category.</div>
      </div>
    </div>
  </div>

  <script>
    // Static playlists
    const staticPlaylists = {
      workout: [
        { song: 'Eye of the Tiger - Survivor', file: 'javascript:;' },
        { song: 'Stronger - Kanye West', file: 'path/to/stronger.mp3' },
        { song: 'Can’t Hold Us - Macklemore & Ryan Lewis', file: 'path/to/cant_hold_us.mp3' },
        { song: 'Lose Yourself - Eminem', file: 'path/to/lose_yourself.mp3' },
        { song: 'Titanium - David Guetta ft. Sia', file: 'path/to/titanium.mp3' }
      ],
      relaxation: [
        { song: 'Weightless - Marconi Union', file: 'path/to/weightless.mp3' },
        { song: 'Someone Like You - Adele', file: 'path/to/someone_like_you.mp3' },
        { song: 'Sunset Lover - Petit Biscuit', file: 'path/to/sunset_lover.mp3' },
        { song: 'River Flows in You - Yiruma', file: 'path/to/river_flows_in_you.mp3' },
        { song: 'Holocene - Bon Iver', file: 'path/to/holocene.mp3' }
      ],
      motivation: [
        { song: 'Don’t Stop Believin’ - Journey', file: 'path/to/dont_stop_believin.mp3' },
        { song: 'We Will Rock You - Queen', file: 'path/to/we_will_rock_you.mp3' },
        { song: 'Happy - Pharrell Williams', file: 'path/to/happy.mp3' },
        { song: 'Fight Song - Rachel Platten', file: 'path/to/fight_song.mp3' },
        { song: 'Zindagi Na Milegi Dobara - Hindi Song', file: 'path/to/zindagi_na_milegi.mp3' }  <!-- Hindi song added -->
      ]
    };

    // Handle category selection
    document.getElementById('category-select').addEventListener('change', function() {
      const category = this.value;
      const playlistContainer = document.getElementById('playlist');
      
      // Clear existing playlist items
      playlistContainer.innerHTML = '';
      
      // Show playlist based on selected category
      if (category !== 'none') {
        const playlist = staticPlaylists[category];
        playlist.forEach((songItem) => {
          const playlistItem = document.createElement('div');
          playlistItem.classList.add('playlist-item');

          // Song title
          const songTitle = document.createElement('div');
          songTitle.textContent = songItem.song;
          playlistItem.appendChild(songTitle);

          // Audio player
          const audio = document.createElement('audio');
          audio.controls = true;
          const source = document.createElement('source');
          source.src = songItem.file;
          source.type = 'audio/mp3';
          audio.appendChild(source);
          playlistItem.appendChild(audio);

          // Download button
          const downloadButton = document.createElement('a');
          downloadButton.href = songItem.file;
          downloadButton.download = songItem.song;
          downloadButton.textContent = 'Download';
          downloadButton.classList.add('download-btn');
          playlistItem.appendChild(downloadButton);

          // Append to playlist container
          playlistContainer.appendChild(playlistItem);
        });
      } else {
        const noPlaylistMessage = document.createElement('div');
        noPlaylistMessage.classList.add('playlist-item');
        noPlaylistMessage.textContent = 'No recommendations available. Please select a category.';
        playlistContainer.appendChild(noPlaylistMessage);
      }
    });
  </script>
</body>
</html>

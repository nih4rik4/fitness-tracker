<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Personalized Playlist</title>
  <link rel="stylesheet" href="Playlist.css"> 
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
        'Eye of the Tiger - Survivor',
        'Stronger - Kanye West',
        'Can’t Hold Us - Macklemore & Ryan Lewis',
        'Lose Yourself - Eminem',
        'Titanium - David Guetta ft. Sia'
      ],
      relaxation: [
        'Weightless - Marconi Union',
        'Someone Like You - Adele',
        'Sunset Lover - Petit Biscuit',
        'River Flows in You - Yiruma',
        'Holocene - Bon Iver'
      ],
      motivation: [
        'Don’t Stop Believin’ - Journey',
        'We Will Rock You - Queen',
        'Happy - Pharrell Williams',
        'Fight Song - Rachel Platten',
        'Hall of Fame - The Script ft. will.i.am'
      ]
    };


    document.getElementById('category-select').addEventListener('change', function() {
      const category = this.value;
      const playlistContainer = document.getElementById('playlist');
      

      playlistContainer.innerHTML = '';
      
    
      if (category !== 'none') {
        const playlist = staticPlaylists[category];
        playlist.forEach((song) => {
          const playlistItem = document.createElement('div');
          playlistItem.classList.add('playlist-item');
          playlistItem.textContent = song;
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
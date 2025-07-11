<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rec4</title>
  <link rel="stylesheet" href="goals.css">
  <style>
          /* Ensure there is enough spacing between the links */
.navbar-nav .nav-item {
    margin-right: 20px; /* Space between links */
}

/* Align the "Log Out" button to the right */
.navbar-nav.ml-auto {
    margin-left: auto;
}

/* Button styles for hover effect */
.btn-logout {
    background-color: #f44336; /* Red background */
    color: white;
    padding: 8px 16px;
    border-radius: 4px;
    text-transform: uppercase;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

/* Hover effect for Log Out button */
.btn-logout:hover {
    background-color: #d32f2f; /* Darker red on hover */
    transform: scale(1.1); /* Slight zoom effect */
}

    </style>
</head>
<body>

  <div class="playlist-section mt-5">
    <!-- <h2>Your Personalized Playlist</h2> -->
  <?php include("ms.php"); ?>
  </div>
  <div class="text-center mt-3">
  <a href="ms.html" class="btn btn-primary">Get Recipe</a>
</div>
  <div class="playlist-section mt-5">
    <!-- <h2>Your Personalized Playlist</h2> -->
  <?php include("playlist.php"); ?>
  </div>
  <div class="container">
    <div class="head">
      <div class="content">
        <p>
          <h1>How does stress affect your physical and mental health?</h1>
          <h5>When you feel stress, you may experience:</h5>
          <ul>
            <li>Anxiety, depression or panic attacks.</li>
            <li>Chest pain or racing heart rate.</li>
            <li>Fatigue or insomnia.</li>
            <li>Headaches.</li>
            <li>Upset stomach (indigestion).</li>
          </ul>
        </p>

        <h2>Surya Namaskar</h2>
        <h4>Instructions:</h4>
        <p>
          <h5>Step 1:</h5> Join your palms and stand straight. Make the salutation: ॐ मित्राय नमः (om mitrāya namaḥ)<br />

          <h5>Step 2:</h5> Raise your hands and stretch them back. Make the salutation: ॐ रवये नमः (om ravaye namaḥ.)<br />
          <h5>Step 3:</h5> Place the right foot at the back, left foot under the torso and look straight. Make the salutation: ॐ भानवे नमः (om bhānava namaḥ)<br />
          <h5>Step 4:</h5> Bend down and try to hold your ankles with your hands. Make the salutation: ॐ सूर्याय नमः (om sūryāya namaḥ)<br />
          <h5>Step 5:</h5> Put both legs together at the back, keep your elbow straight and keep your spine straight. Make the salutation: ॐ खगाय नमः (om khagāya namaḥ)<br />

          <h5>Step 6:</h5> Bend your elbows and push your body towards the floor, keeping it stiff like a push-up. Make the salutation: ॐ पूष्णे नमः (om pūṣṇe namaḥ).<br />

          <h5>Step 7:</h5> Push your hips towards the floor, hands straight, chest up and stretch your shoulders up. Make the salutation: ॐ हिरण्यगर्भाय नमः (om hiraṇya garbhāya namaḥ)<br />

          <h5>Step 8:</h5> Keep your hands in the same position, raise your hip and back to form a curve. Make the salutation: ॐ मरीचये नमः (om marīcaye namaḥ)<br />

          <h5>Step 9:</h5> Retract to form position as in step 4 using the opposite legs. Make the salutation: ॐ आदित्याय नमः (om ādityāya namaḥ)<br />

          <h5>Step 10:</h5> Retract to form position as in step 3. Make the salutation: ॐ सवित्रे नमः (om savitre namaḥ).<br />

          <h5>Step 11:</h5> Stand straight as you raise your hands above your head. This is the same as step 2, without a stretch. Make the salutation: ॐ अर्काय नमः (om arkāya namaḥ).<br />

          <h5>Step 12:</h5> Bring your hands back to the position in step 1. Make the salutation: ॐ भास्कराय नमः (om bhāskarāya namaḥ).<br />
        </p>

        <div style="display: flex; align-items: center; gap: 20px; padding: 20px;">
          <!-- <img src="Surya.webp" alt="description" style="width: 400px; height: 300px; border-radius: 10px;"> -->
          <div class="video-container" style="flex: 1; max-width: 1000px; height: 300px;">
            <iframe src="https://www.youtube.com/embed/1xRX1MuoImw" title="Squats Guide"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen style="width: 100%; height: 420px; border-radius: 10px; border: none;"></iframe>
          </div>
        </div>

        <h2>Cognitive techniques:</h2>
        <h5>Keep a journal:</h5> Write down the day’s accomplishments. You can also capture positive events of the day or three things you’re grateful for.<br/>
        <h5>Make “me time”:</h5> Try to do at least one thing a day that’s just for you. It could be meditating, getting together with a friend, reading a book or working on a hobby.<br/>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; padding: 20px;">
          <div style="align-items: center; gap: 15px;">
            <h2>Gardening</h2>
            <img src="Gardening.webp" alt="Gardening" style="width: 250px; height: 250px; border-radius: 15px;">
          </div>

          <div style="align-items: center; gap: 15px;">
            <h2>Drawing, Painting, and Coloring</h2>
            <img src="Draw.webp" alt="Drawing" style="width: 250px; height: 250px; border-radius: 15px;">
          </div>

          <div style="align-items: center; gap: 15px;">
            <h2>Keep a Journal</h2>
            <img src="Journal.webp" alt="Journal" style="width: 250px; height: 250px; border-radius: 15px;">
          </div>

          <div style="align-items: center; gap: 15px;">
            <h2>Playing an Instrument</h2>
            <img src="Inst.webp" alt="Instrument" style="width: 250px; height: 250px; border-radius: 15px;">
          </div>
        </div>

        <h2>When To Call the Doctor</h2>
        <h3>When should I talk to a doctor about stress?</h3>
        <h3>You should call your healthcare provider if you experience:</h3>
        <ul>
          <li>Anxiety or depression.</li>
          <li>Chest pain.</li>
          <li>Substance abuse.</li>
          <li>Suicidal thoughts.</li>

        </ul>
        
      </div>
    </div>
  </div>
</body>
</html>
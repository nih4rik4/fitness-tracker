<?php
// Start the session to retrieve user_id
session_start();

// Connect to the database
$servername = "localhost";
$port = "3306";
$username = "root";
$password = "";
$dbname = "fitnesstracker";

$pdo = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if the user is logged in by checking session
if (!isset($_SESSION['user_id'])) {
    echo "You are not logged in.";
    exit();
}

$user_id = $_SESSION['user_id'];  // Get the user ID from the session

// Fetch user information
$userQuery = $pdo->prepare("SELECT user_name, email, dob, weight, height, gender, age FROM users WHERE user_id = :user_id");
$userQuery->execute(['user_id' => $user_id]);
$user = $userQuery->fetch(PDO::FETCH_ASSOC);

// Fetch the user's goal(s)
$goalQuery = $pdo->prepare("SELECT g.Goal_type, u.target_value FROM user_goals_table u
                            JOIN goal_table g ON u.goal_id = g.Goal_id
                            WHERE u.user_id = :user_id");
$goalQuery->execute(['user_id' => $user_id]);
$goals = $goalQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.profile-container {
    width: 80%;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h1 {
    text-align: center;
    color: #333;
}

.user-details p, .user-goals p {
    font-size: 20px;
    line-height: 1.8;
}

.user-details strong, .user-goals strong {
    color: #333;
}

.user-goals ul {
    list-style-type: none;
    padding: 0;
}

.user-goals li {
    background-color: #f9f9f9;
    padding: 10px;
    margin: 5px 0;
    border-radius: 5px;
}

.user-goals li:nth-child(odd) {
    background-color: #e9e9e9;
}
.fa-solid.fa-user {
            color: black;
            font-size: 70px;
            display: block;
            text-align: left;
            margin: 0 auto 20px;
        }

     </style>
</head>
<body>
    <div class="profile-container">
        <h1>User Profile</h1>
        <i class="fa-solid fa-user"></i>
        <!-- Display user details -->
        <div class="user-details">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['user_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['dob']); ?></p>
            <p><strong>Weight:</strong> <?php echo htmlspecialchars($user['weight']); ?> kg</p>
            <p><strong>Height:</strong> <?php echo htmlspecialchars($user['height']); ?> cm</p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($user['age']); ?> years</p>
        </div>

        <!-- Display user goals -->
        <h2>Goals</h2>
        <div class="user-goals">
            <?php if ($goals): ?>
                <ul>
                    <?php foreach ($goals as $goal): ?>
                        <li>
                        <strong style="font-size:20px;"><?php echo htmlspecialchars(ucwords($goal['Goal_type'])); ?></strong><br></br>


                            <!-- <?php echo htmlspecialchars($goal['target_value']); ?> -->
                          <strong> <a class="user-goals" href="progress_graph.html">View your progress</a> </strong>

                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No goals set yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
session_start(); // Start the session to access the user_id

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fitnesstracker";
$port = 3306;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in by checking if user_id is in the session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; // Get the user_id from the session

    // Query to fetch user-specific data from the database
    $sql = "SELECT date, calories_burnt, sleep, water, weight FROM progress_tracking WHERE user_id = '$user_id' ORDER BY date ASC";
    $result = $conn->query($sql);

    // Prepare data in JSON format
    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    // Output JSON data
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    // If the user is not logged in, redirect to login page
    header('Location: Login.html');
}

$conn->close();
?>

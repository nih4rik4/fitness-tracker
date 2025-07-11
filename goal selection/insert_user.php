<?php
header("Access-Control-Allow-Origin: http://localhost:3000"); // React App URL
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost:3307"; // MySQL server running on port 3307
$username = "root"; // MySQL user_name
$password = ""; // MySQL password
$dbname = "bhanu"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user data from the POST request
$data = json_decode(file_get_contents("php://input"));
$user_name = $data->user_name;
$age = $data->age;

// Insert user data into USER_TABLE
$sql = "INSERT INTO USERS(user_name, age) VALUES ('$user_name', '$age')";

if ($conn->query($sql) === TRUE) {
    $user_id = $conn->insert_id; // Get the inserted user ID
    echo json_encode(["success" => true, "user_id" => $user_id]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

$conn->close();
?>

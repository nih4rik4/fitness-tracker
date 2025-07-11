<?php
session_start();
// Enable CORS if necessary
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle OPTIONS request (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Only POST requests are allowed.']);
    exit();
}

// Get the data from POST request
$data = $_POST;

// Validate required fields
$requiredFields = ['steps', 'rigorous_minutes', 'semi_rigorous_minutes', 'weight', 'water', 'sleep', 'date'];
foreach ($requiredFields as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => "Field '$field' is required."]);
        exit();
    }
}

// Sanitize input data
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];} 
$steps = filter_var($data['steps'], FILTER_VALIDATE_INT);
$rigorous_minutes = filter_var($data['rigorous_minutes'], FILTER_VALIDATE_INT);
$semi_rigorous_minutes = filter_var($data['semi_rigorous_minutes'], FILTER_VALIDATE_INT);
$weight = filter_var($data['weight'], FILTER_VALIDATE_FLOAT);
$water = filter_var($data['water'], FILTER_VALIDATE_INT);
$sleep = filter_var($data['sleep'], FILTER_VALIDATE_FLOAT);
$date = $data['date']; // Expect date in YYYY-MM-DD format

// Check for invalid data
if ($steps === false || $rigorous_minutes === false || $semi_rigorous_minutes === false || $weight === false || $water === false || $sleep === false) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid data types for one or more fields.']);
    exit();
}

// Calculate calories burnt
$calories_burnt = ($steps * 0.05) + ($rigorous_minutes * 10) + ($semi_rigorous_minutes * 6);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fitnesstracker";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert data into the table
$sql = "INSERT INTO progress_tracking (user_id, date, calories_burnt, water, sleep, weight) 
        VALUES ('$user_id', '$date', '$calories_burnt', '$water', '$sleep', '$weight')";

if ($conn->query($sql) === TRUE) {
    echo json_encode([
        'success' => true, 
        'message' => 'Progress data submitted successfully.',
        'redirect' => 'progress_graph.html' // Include redirect URL
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
}

$conn->close();
?>

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];
$date = json_decode(file_get_contents('php://input'), true)['date'] ?? null;

if (!$date) {
    echo json_encode(['status' => 'error', 'message' => 'No date specified']);
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fitnesstracker";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query for entries on the specified date
    $stmt = $pdo->prepare("SELECT * FROM FOOD_INTAKE_DIARY WHERE user_id = :user_id AND intake_date = :intake_date");
    $stmt->execute(['user_id' => $userId, 'intake_date' => $date]);

    $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'entries' => $entries]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>

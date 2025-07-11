<?php
session_start(); // Start the session

// Include your database connection
require_once 'conn.php'; // Include your actual DB connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html"); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Get the user ID from the session

// Query to fetch the user's goal
$query = "SELECT goal_id FROM user_goals_table WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id); // 'i' for integer (user_id is assumed to be an integer)
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_goal = $result->fetch_assoc();
    $goal_id = $user_goal['goal_id'];

    // Use the goal_id to decide which page to load
    switch ($goal_id) {
        case 1:
            // Redirect to the page for goal 1
            header("Location: gainweight.php");
            break;
        case 2:
            // Redirect to the page for goal 2
            header("Location: loseweight.php");
            break;
        case 3:
            // Redirect to the page for goal 3
            header("Location: maintainweight.php");
            break;
        case 4:
            // Redirect to the page for goal 4
            header("Location: improveindurance.php");
            break;
        case 5:
            // Redirect to the page for goal 5
            header("Location: managestress.php");
            break;
        default:
            // Default page if the goal_id doesn't match
            echo "Invalid goal.";
            break;
    }
} else {
    echo "No goal found for this user.";
}
?>

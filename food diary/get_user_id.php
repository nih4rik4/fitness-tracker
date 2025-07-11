<?php
// get_user_id.php
session_start();

// Check if the user is logged in and has a session
if (isset($_SESSION['user_id'])) {
    echo json_encode(['user_id' => $_SESSION['user_id']]);
} else {
    echo json_encode(['error' => 'User not logged in']);
}
?>
<?php
session_start(); // Start the session

// Include your database connection
require_once 'conn.php'; // Replace with your actual DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $username = $_POST['user_name'];
    $password = $_POST['pass_word'];

    // Prepare SQL query to fetch the user from the database
    $query = "SELECT * FROM users WHERE user_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username); // 's' denotes string parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user data from the database
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['pass_word'])) {
            // If the password is correct, start the session and set session variables
            $_SESSION['user_id'] = $user['user_id']; // Assuming the column 'id' contains the user's ID
            $_SESSION['username'] = $user['user_name'];

            // Redirect to the food diary page or another page after successful login
            header('Location: homepage.html');
            exit;
        } else {
            // If the password is incorrect, display an error
            echo "Invalid password.";
        }
    } else {
        // If no user is found, display an error
        echo "User not found.";
    }
} else {
    // If the form is not submitted, redirect to the login page
    header('Location: login.html');
    exit;
}
?>

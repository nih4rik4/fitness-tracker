<?php
// Start the session
session_start();

// Include your database connection
require_once 'conn.php'; // Replace with your actual DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $username = $_POST['user_name'];
    $password = $_POST['pass_word'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];

    // Validate passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match. Please try again.";
        exit; // Stop script execution if passwords don't match
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if username already exists in the database
    $query = "SELECT * FROM users WHERE user_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username is already taken. Please choose a different username.";
        exit; // Stop script execution if username is already taken
    }

    // Prepare the SQL query to insert the new user
    $insert_query = "INSERT INTO users (user_name, pass_word, email, age, gender, dob, weight, height) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param('ssssssdd', $username, $hashed_password, $email, $age, $gender, $dob, $weight, $height);

    if ($insert_stmt->execute()) {
        // Successful registration
        echo "Registration successful!";
        
        // Optionally, redirect to the login page after successful registration
        header('Location: login.html'); // Redirect to login page
        exit;
    } else {
        // Error inserting user
        echo "An error occurred while registering the user.";
    }
} else {
    // If the form is not submitted, redirect to the signup page
    header('Location: signup.html');  // Ensure this is the correct path for your signup form
    exit;
}
?>

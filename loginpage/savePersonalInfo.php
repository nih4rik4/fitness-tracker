<?php
// Database connection
$servername = "localhost";
$username = "root";  // Default username for MySQL in XAMPP
$password = "";      // Default password for MySQL in XAMPP
$dbname = "your_database"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data from AJAX
$username = $_POST['username'];
$gender = $_POST['gender'];
$dob = $_POST['dob'];
$weight = $_POST['weight'];
$height = $_POST['height'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security

// Prepare SQL query to insert data into the users table
$sql = "INSERT INTO users (username, gender, dob, weight, height, email, password)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

// Prepare statement
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bind_param("sssssss", $username, $gender, $dob, $weight, $height, $email, $password);

// Execute query and check if it was successful
if ($stmt->execute()) {
    echo "Data inserted successfully";
} else {
    echo "Error: " . $stmt->error;
}

// Close the connection
$stmt->close();
$conn->close();
?>

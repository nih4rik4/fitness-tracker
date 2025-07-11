<?php
$DB_USERNAME = "root";
$DB_PASS = "";
$DB_HOSTNAME = "localhost:3306";
$DB_NAME = "fitnesstracker";

// Create connection
$conn = mysqli_connect($DB_HOSTNAME, $DB_USERNAME, $DB_PASS, $DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


?>
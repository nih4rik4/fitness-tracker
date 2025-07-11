<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$servername = "localhost";
$port = "3306";
$username = "root";
$password = "";
$dbname = "fitnesstracker";
session_start(); 

try {
    // Establish a connection to the database
    $pdo = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the date from the GET request
    if (isset($_GET['date'])) {
        $date = $_GET['date'];
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Date not specified']);
        exit;
    }

    // Prepare SQL query to fetch the food intake data for the specified date, joining FOOD_INTAKE and FOOD_NUTRITIONAL_FACTS
    $stmt = $pdo->prepare("
        SELECT fi.mealtype, 
               fn.foodname, 
               fn.calories, 
               fn.total_carbs AS carbs, 
               fn.total_fat AS fat, 
               fn.protein, 
               fn.sodium, 
               fn.sugars
        FROM FOOD_INTAKE fi
        INNER JOIN FOOD_NUTRITIONAL_FACTS fn ON fi.foodid = fn.foodid
        WHERE fi.intake_date = :date
    ");
    $stmt->execute(['intake_date' => $date]);

    // Fetch the data and group by meal type
    $mealData = [
        'breakfast' => [],
        'lunch' => [],
        'dinner' => [],
        'snacks' => []
    ];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $mealData[$row['mealtype']][] = $row;
    }

    // Return the data as JSON
    echo json_encode($mealData);
} catch (PDOException $e) {
    // Return error message if there is a database issue
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>

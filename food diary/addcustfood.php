<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // Start session

// Check if the user is logged in (via session)
if (!isset($_SESSION['user_id'])) {
    // If no user is logged in, return error response
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

// User is logged in, so fetch user_id from session
$userId = $_SESSION['user_id'];

// Database connection details
$servername = "localhost";
$port = "3306";
$username = "root";
$password = "";
$dbname = "fitnesstracker";

// Set the content type to JSON
header('Content-Type: application/json');

try {
    // Create a PDO connection
    $pdo = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the JSON input from the request body
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input']);
        exit;
    }
    
    // Check if the necessary data is provided in the request
    if (isset($input['customFoodName'], $input['calories'], $input['serving'], $input['quantity'], $input['mealType'])) {
        $foodName = $input['customFoodName'];
        $calories = (float)$input['calories'];
        $protein = isset($input['protein']) ? (float)$input['protein'] : 0;
        $carbs = isset($input['carbs']) ? (float)$input['carbs'] : 0;
        $fat = isset($input['fat']) ? (float)$input['fat'] : 0;
        $sodium = isset($input['sodium']) ? (float)$input['sodium'] : 0;
        $sugars = isset($input['sugars']) ? (float)$input['sugars'] : 0;
        $servingSize = $input['serving'];
        $quantity = (int)$input['quantity'];
        $mealType = $input['mealType'];

        // Current date for tracking the food intake
        $currentDate = date('Y-m-d');

        // Insert the food intake details into the FOOD_INTAKE_DIARY table
        $insertStmt = $pdo->prepare("
            INSERT INTO FOOD_INTAKE_DIARY 
            (user_id, foodname, meal_type, intake_date, serving_size, quantity, total_calories, total_carbs, total_fat, total_protein, sodium, sugars)
            VALUES (:user_id, :foodname, :meal_type, :intake_date, :serving_size, :quantity, :total_calories, :total_carbs, :total_fat, :total_protein, :sodium, :sugars)
        ");
        
        // Execute the insert query
        $insertStmt->execute([
            'user_id' => $userId,
            'foodname' => $foodName,
            'meal_type' => $mealType,
            'intake_date' => $currentDate,
            'serving_size' => $servingSize,
            'quantity' => $quantity,
            'total_calories' => $calories * $quantity,
            'total_carbs' => $carbs * $quantity,
            'total_fat' => $fat * $quantity,
            'total_protein' => $protein * $quantity,
            'sodium' => $sodium * $quantity,
            'sugars' => $sugars * $quantity
        ]);

        // Return success message in JSON format
        echo json_encode(['status' => 'success', 'message' => 'Food added to diary successfully']);
    } else {
        // If input data is invalid
        echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
    }
} catch (PDOException $e) {
    // If a database error occurs, return error message
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>

<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); 


if (!isset($_SESSION['user_id'])) {
   
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}


$userId = $_SESSION['user_id'];


$servername = "localhost";
$port = "3306";
$username = "root";
$password = "";
$dbname = "fitnesstracker";


header('Content-Type: application/json');

try {
    
    $pdo = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $input = json_decode(file_get_contents('php://input'), true);

    // Check if the necessary data (foodId, serving, quantity, mealType) is provided in the request
    if (isset($input['foodId'], $input['serving'], $input['quantity'], $input['mealType'])) {
        $foodId = $input['foodId'];
        $servingSize = $input['serving'];
        $quantity = (int)$input['quantity'];
        $mealType = $input['mealType'];
        
        // Current date for tracking the food intake
         $currentDate = date('Y-m-d');
               // $currentDate = date('Y-m-d');


        // Query to fetch food details from FOOD_NUTRITIONAL_FACTS table
        $stmt = $pdo->prepare("SELECT * FROM FOOD_NUTRITIONAL_FACTS WHERE foodid = :foodid");
        $stmt->execute(['foodid' => $foodId]);
        $food = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($food) {
            // Calculate the total nutritional values based on quantity
            $totalCalories = $food['calories'] * $quantity;
            $totalCarbs = $food['total_carbs'] * $quantity;
            $totalFat = $food['total_fat'] * $quantity;
            $totalProtein = $food['protein'] * $quantity;
            $sodium = $food['sodium'] * $quantity;
            $sugars = $food['sugars'] * $quantity;

            // Insert the food intake details into the FOOD_INTAKE_DIARY table
            $insertStmt = $pdo->prepare("
                INSERT INTO FOOD_INTAKE_DIARY 
                (user_id, foodid, foodname, meal_type, intake_date, serving_size, quantity, total_calories, total_carbs, total_fat, total_protein, sodium, sugars)
                VALUES (:user_id, :foodid, :foodname, :meal_type, :intake_date, :serving_size, :quantity, :total_calories, :total_carbs, :total_fat, :total_protein, :sodium, :sugars)
            ");
            
            // Execute the insert query
            $insertStmt->execute([
                'user_id' => $userId,
                'foodid' => $foodId,
                'foodname' => $food['foodname'],
                'meal_type' => $mealType,
                'intake_date' => $currentDate,
                'serving_size' => $servingSize,
                'quantity' => $quantity,
                'total_calories' => $totalCalories,
                'total_carbs' => $totalCarbs,
                'total_fat' => $totalFat,
                'total_protein' => $totalProtein,
                'sodium' => $sodium,
                'sugars' => $sugars
            ]);

            // Return success message in JSON format
            echo json_encode(['status' => 'success', 'message' => 'Food added to diary successfully']);
        } else {
            // If food item not found in the database, return error message
            echo json_encode(['status' => 'error', 'message' => 'Food item not found']);
        }
    } else {
        // If input data is invalid (missing foodId, serving, quantity, or mealType)
        echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
    }
} catch (PDOException $e) {
    // If a database error occurs, return error message
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
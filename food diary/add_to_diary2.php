<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$port = "3306";
$username = "root";
$password = "";
$dbname = "fitnesstracker";

try {
    $pdo = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get the JSON data sent from the JavaScript fetch request
    $data = json_decode(file_get_contents('php://input'), true);

    // Extracting variables from the JSON data
    $customFoodName = $data['customFoodName'] ?? null;
    $calories = $data['calories'] ?? null;
    $protein = $data['protein'] ?? null;
    $carbs = $data['carbs'] ?? null;
    $fat = $data['fat'] ?? null;
    $serving = $data['serving'] ?? null;
    $quantity = $data['quantity'] ?? null;
    $mealType = $data['mealType'] ?? null;
    $foodid=

    // Validate input data
    if (!$customFoodName || !$calories || !$serving || !$quantity || !$mealType) {
        echo json_encode(['status' => 'error', 'message' => 'Missing or invalid input data']);
        exit;
    }

    // Insert data into the FOOD_INTAKE table
    $stmt = $pdo->prepare("INSERT INTO FOOD_INTAKE (serving, quantity, mealtype) VALUES (?, ?, ?");
    $stmt->execute([$customFoodName, $calories, $protein, $carbs, $fat, $serving, $quantity, $mealType]);

    // Return success response
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
header("Location: fooddiaryrev.php");
exit();
?>


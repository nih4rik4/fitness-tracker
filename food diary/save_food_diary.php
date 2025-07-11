<?php
// save_food_diary.php
$servername = "localhost";
$port = "3306";
$username = "root";
$password = "";
$dbname = "fitnesstracker";

// Connect to the database
$pdo = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
session_start(); 
// Assuming the data is passed via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = 1; // Hardcoded for now, update this based on session/user login
    $mealTypes = ['breakfast', 'lunch', 'dinner', 'snacks'];
    $date = date('Y-m-d'); // Get current date for the diary entry
    
    // Loop through each meal type and insert the food data into the database
    foreach ($mealTypes as $mealType) {
        if (isset($_POST[$mealType])) {
            foreach ($_POST[$mealType] as $food) {
                $stmt = $pdo->prepare("
                    INSERT INTO FOOD_INTAKE_DIARY (user_id, meal_type, intake_date, foodname, total_calories, total_carbs, total_fat, total_protein, sodium, sugars)
                    VALUES (:user_id, :meal_type, :intake_date, :foodname, :total_calories, :total_carbs, :total_fat, :total_protein, :sodium, :sugars)
                ");
                
                $stmt->execute([
                    'user_id' => $user_id,
                    'meal_type' => $mealType,
                    'intake_date' => $date,
                    'foodname' => $food['foodname'],
                    'total_calories' => $food['total_calories'],
                    'total_carbs' => $food['total_carbs'],
                    'total_fat' => $food['total_fat'],
                    'total_protein' => $food['total_protein'],
                    'sodium' => $food['sodium'],
                    'sugars' => $food['sugars']
                ]);
            }
        }
    }
    // Redirect or display confirmation
    echo json_encode(['status' => 'success']);
}
?>

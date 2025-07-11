<?php
// Start the PHP session to access session variables
session_start();

// Set the content type for JSON response
header('Content-Type: application/json');

try {
    // Connect to the database
    $servername = "localhost";
    $port = "3306";
    $username = "root";
    $password = "";
    $dbname = "fitnesstracker";

    $pdo = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve user_id from the session
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        // If the user is not logged in, return an error
        echo json_encode(['success' => false, 'error' => ['User not logged in']]);
        exit();
    }

    // Get the incoming data from the request
    $data = json_decode(file_get_contents("php://input"), true);
    $goal_id = $data['goal_id'];
    $target_value = isset($data['target_value']) ? $data['target_value'] : null;

    // Validate the data
    if (empty($goal_id)) {
        echo json_encode(['success' => false, 'error' => ['Goal ID is required']]);
        exit();
    }

    // If the goal is "Gain Weight" or "Lose Weight", ensure target_value is provided
    if (in_array($goal_id, [1, 2]) && empty($target_value)) {
        echo json_encode(['success' => false, 'error' => ['Target value is required for this goal']]);
        exit();
    }

    // Insert the goal into the user_goals_table
    $stmt = $pdo->prepare("INSERT INTO user_goals_table (user_id, goal_id, target_value) VALUES (:user_id, :goal_id, :target_value)");

    // If target_value is not provided, insert NULL for target_value
    $stmt->execute([
        'user_id' => $user_id,
        'goal_id' => $goal_id,
        'target_value' => $target_value ?? null  // Use null if target_value is not provided
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    // Catch database errors and output them
    echo json_encode(['success' => false, 'error' => [$e->getMessage()]]);
}
?>

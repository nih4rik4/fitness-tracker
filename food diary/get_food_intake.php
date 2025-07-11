<?php
include 'conn.php';
session_start(); // Start session

$date = $_GET['date'];

try {
    $stmt = $pdo->prepare("SELECT * FROM FOOD_INTAKE WHERE date = ?");
    $stmt->execute([$date]);
    $foodIntake = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['status' => 'success', 'data' => $foodIntake]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>

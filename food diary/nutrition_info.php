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

    if (isset($_GET['foodid']) && is_numeric($_GET['foodid'])) {
        $foodid = $_GET['foodid'];

        $stmt = $pdo->prepare("SELECT * FROM FOOD_NUTRITIONAL_FACTS WHERE foodid = :foodid");
        $stmt->execute(['foodid' => $foodid]);
        $food = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$food) {
            echo "No nutritional information found for this item.";
            exit;
        }
    } else {
        echo "Invalid food ID.";
        exit;
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutritional Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f5f9;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .nutrition-list li {
            margin-bottom: 10px;
        }
        .button {
            background-color: #0078d7;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #005bb5;
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px;
        }
        .input-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Nutritional Information for <?= htmlspecialchars($food['foodname']) ?></h2>
        <ul class="nutrition-list">
            <li>Calories: <?= htmlspecialchars($food['calories']) ?> kcal</li>
            <li>Protein: <?= htmlspecialchars($food['protein']) ?> g</li>
            <li>Carbohydrates: <?= htmlspecialchars($food['total_carbs']) ?> g</li>
            <li>Fats: <?= htmlspecialchars($food['total_fat']) ?> g</li>
        </ul>

        <div class="input-group">
            <label for="servingSize">Serving Size:</label>
            <input type="text" id="servingSize" placeholder="e.g., 1 cup">
        </div>
        <div class="input-group">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" min="1" value="1">
        </div>
        
        <button class="button" onclick="addFoodToDiary()">Add to Diary</button>
    </div>

    <script>
    function addFoodToDiary() {
        const foodId = <?= json_encode($food['foodid']); ?>;
        const servingSize = document.getElementById('servingSize').value;
        const quantity = document.getElementById('quantity').value;
        const mealType = prompt("Enter Meal Type (e.g., breakfast, lunch, dinner):");

        if (!servingSize || !quantity || !mealType) {
            alert("Please fill all the fields.");
            return;
        }

        fetch('add_to_diary.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                foodId: foodId,
                serving: servingSize,
                quantity: quantity,
                mealType: mealType.toLowerCase()
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Food added to diary successfully!');
                window.location.href = 'fooddiary.html';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Fetch error:', error));
    }
    </script>
</body>
</html>

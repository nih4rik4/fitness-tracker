<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); 


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
            margin: 0;
            padding: 0;
        }

        .container {
            width: 60%;
            margin: 20px auto;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .nutrition-list {
            list-style: none;
            padding: 0;
        }

        .nutrition-list li {
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }

        .back-link, .add-food {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-link {
            background-color: #0078d7;
            border:none;
        }
        .input-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }
        .input-groupp {
    display: flex;
    flex-direction: column;
    margin-bottom: 10px;
}

.input-groupp label {
    font-weight: bold;
    margin-bottom: 5px;
    color: #555;
}

.input-groupp input {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    width: 50px;  /* Adjust the width for input fields */
}

.input-groupp select {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 15px;
    width: 150px;  /* Adjust the width for the dropdown (smaller width) */
}

        .input-group input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .input-group label{
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;

        }
        .input-group-row {
            display: flex;
            gap: 20px; /* Space between Serving Size and Quantity fields */
            align-items: center;
        }
        .input-group-row .input-group {
            flex: 1;
        }
        .button{
          display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            background-color: #0078d7;
            border:none;
            font-size: 17px;
        }
        .button:hover{
          background-color: #005bb5;
        }

        .back-link:hover {
            background-color: #005bb5;
        }

        .add-food {
            background-color: #4CAF50;
            margin-left: 10px;
        }

        .add-food:hover {
            background-color: #45a049;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">Nutritional Information for <?= htmlspecialchars($food['foodname']) ?></div>
        <ul class="nutrition-list">
            <li>Calories: <?= htmlspecialchars($food['calories']) ?> kcal</li>
            <li>Total Fat: <?= htmlspecialchars($food['total_fat']) ?> g</li>
            <li>Saturated Fat: <?= htmlspecialchars($food['saturated_fat']) ?> g</li>
            <li>Trans Fat: <?= htmlspecialchars($food['trans_fat']) ?> g</li>
            <li>Cholesterol: <?= htmlspecialchars($food['cholesterol']) ?> mg</li>
            <li>Sodium: <?= htmlspecialchars($food['sodium']) ?> mg</li>
            <li>Total Carbohydrates: <?= htmlspecialchars($food['total_carbs']) ?> g</li>
            <li>Dietary Fiber: <?= htmlspecialchars($food['dietary_fiber']) ?> g</li>
            <li>Sugars: <?= htmlspecialchars($food['sugars']) ?> g</li>
            <li>Protein: <?= htmlspecialchars($food['protein']) ?> g</li>
            <li>Vitamin A: <?= htmlspecialchars($food['vit_a']) ?> %</li>
            <li>Vitamin C: <?= htmlspecialchars($food['vit_c']) ?> %</li>
            <li>Calcium: <?= htmlspecialchars($food['calcium']) ?> %</li>
            <li>Iron: <?= htmlspecialchars($food['iron']) ?> %</li>
        </ul>
        <!-- Serving Size and Quantity Inputs with improved spacing and styling -->
        <div class="input-group-row">
            <div class="input-group">
                <label for="servingSize">Serving Size (e.g., 1 cup, 100g):</label>
                <input type="text" id="servingSize" placeholder="Enter serving size">
            </div>
            <div class="input-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" placeholder="Enter quantity" min="1" value="1">
            </div>
        </div>
        <div class="input-groupp">
    <label for="mealType">Meal Type:</label>
    <select id="mealType">
        <option value="breakfast">Breakfast</option>
        <option value="lunch">Lunch</option>
        <option value="dinner">Dinner</option>
        <option value="snacks">Snack</option>
    </select>
</div>

       
        <!-- Back link and Add to Diary button -->
        <a href="search_food.php" class="back-link">Back to Search</a>
        <button class="button" onclick="addFoodToDiary()">Add to Diary</button>
    </div>

    <script>
   
    function addFoodToDiary() {
    const foodId = <?= json_encode($food['foodid']); ?>;
    const servingSize = document.getElementById('servingSize').value;
    const quantity = document.getElementById('quantity').value;
    const mealType = document.getElementById('mealType').value;  // Use dropdown value

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
            alert(data.message);
            window.location.href = 'fooddiaryrev.php'; // Redirect to the food diary page
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => console.error('Fetch error:', error));
}



    </script>
</body>
</html>

<?php
// Database connection
$servername = "localhost";
$port = "3306";
$username = "root";
$password = "";
$dbname = "fitnesstracker";

try {
    $pdo = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect input data
    $foodname = $_POST['foodname'];
    $calories = $_POST['calories'];
    $total_fat = $_POST['total_fat'] ?: NULL;
    $saturated_fat = $_POST['saturated_fat'] ?: NULL;
    $trans_fat = $_POST['trans_fat'] ?: NULL;
    $cholesterol = $_POST['cholesterol'] ?: NULL;
    $sodium = $_POST['sodium'] ?: NULL;
    $total_carbs = $_POST['total_carbs'] ?: NULL;
    $dietary_fiber = $_POST['dietary_fiber'] ?: NULL;
    $sugars = $_POST['sugars'] ?: NULL;
    $protein = $_POST['protein'] ?: NULL;
    $vit_a = $_POST['vit_a'] ?: NULL;
    $vit_c = $_POST['vit_c'] ?: NULL;
    $calcium = $_POST['calcium'] ?: NULL;
    $iron = $_POST['iron'] ?: NULL;

    // SQL query
    $query = "INSERT INTO food_nutritional_facts (foodname, calories, total_fat, saturated_fat, trans_fat, cholesterol, sodium, 
                total_carbs, dietary_fiber, sugars, protein, vit_a, vit_c, calcium, iron)
              VALUES (:foodname, :calories, :total_fat, :saturated_fat, :trans_fat, :cholesterol, :sodium, 
                :total_carbs, :dietary_fiber, :sugars, :protein, :vit_a, :vit_c, :calcium, :iron)";

    $stmt = $pdo->prepare($query);

    // Execute with parameters
    if ($stmt->execute([
        ':foodname' => $foodname,
        ':calories' => $calories,
        ':total_fat' => $total_fat,
        ':saturated_fat' => $saturated_fat,
        ':trans_fat' => $trans_fat,
        ':cholesterol' => $cholesterol,
        ':sodium' => $sodium,
        ':total_carbs' => $total_carbs,
        ':dietary_fiber' => $dietary_fiber,
        ':sugars' => $sugars,
        ':protein' => $protein,
        ':vit_a' => $vit_a,
        ':vit_c' => $vit_c,
        ':calcium' => $calcium,
        ':iron' => $iron
    ])) {
        echo "<script>alert('Food item added successfully!');</script>";
    } else {
        echo "<script>alert('Failed to add food item.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }

        .form-container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-container label {
            display: block;
            margin: 10px 0 5px;
        }

        .form-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Add New Food Items</h1>
        <form method="POST" action="foodadmin.php">
            <label for="foodname">Food Name</label>
            <input type="text" id="foodname" name="foodname" required>

            <label for="calories">Calories</label>
            <input type="number" step="0.01" id="calories" name="calories" required>

            <label for="total_fat">Total Fat</label>
            <input type="number" step="0.01" id="total_fat" name="total_fat">

            <label for="saturated_fat">Saturated Fat</label>
            <input type="number" step="0.01" id="saturated_fat" name="saturated_fat">

            <label for="trans_fat">Trans Fat</label>
            <input type="number" step="0.01" id="trans_fat" name="trans_fat">

            <label for="cholesterol">Cholesterol</label>
            <input type="number" step="0.01" id="cholesterol" name="cholesterol">

            <label for="sodium">Sodium</label>
            <input type="number" step="0.01" id="sodium" name="sodium">

            <label for="total_carbs">Total Carbs</label>
            <input type="number" step="0.01" id="total_carbs" name="total_carbs">

            <label for="dietary_fiber">Dietary Fiber</label>
            <input type="number" step="0.01" id="dietary_fiber" name="dietary_fiber">

            <label for="sugars">Sugars</label>
            <input type="number" step="0.01" id="sugars" name="sugars">

            <label for="protein">Protein</label>
            <input type="number" step="0.01" id="protein" name="protein">

            <label for="vit_a">Vitamin A</label>
            <input type="number" step="0.01" id="vit_a" name="vit_a">

            <label for="vit_c">Vitamin C</label>
            <input type="number" step="0.01" id="vit_c" name="vit_c">

            <label for="calcium">Calcium</label>
            <input type="number" step="0.01" id="calcium" name="calcium">

            <label for="iron">Iron</label>
            <input type="number" step="0.01" id="iron" name="iron">

            <button type="submit">Add Food Item</button>
        </form>
    </div>
</body>
</html>

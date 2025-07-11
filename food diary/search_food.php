<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$port = "3306";
$username = "root";
$password = "";
$dbname = "fitnesstracker";
session_start(); 
try {
    $pdo = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Variable to store search results
    $results = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['search'])) {
        $searchTerm = htmlspecialchars($_POST['search']);
        
        $stmt = $pdo->prepare("SELECT * FROM FOOD_NUTRITIONAL_FACTS WHERE foodname LIKE :searchTerm");
        $stmt->execute(['searchTerm' => "%$searchTerm%"]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Food Items</title>
    <style>
        /* Shared styles */
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

        .search-form input[type="text"] {
            padding: 8px;
            font-size: 1em;
            width: 50%;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-form button {
            padding: 10px;
            background-color: #0078d7;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            width:120px;
            
        }

        .search-form button:hover {
            background-color: #005bb5;
        }

        .results-section ul {
            list-style-type: none;
            padding: 0;
        }

        .results-section li {
            padding: 8px;
            border: 1px solid #ddd;
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .results-section a {
            color: #0078d7;
            text-decoration: none;
            font-weight: bold;
        }

        .results-section a:hover {
            text-decoration: underline;
        }
        .back-link {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 25px; /* Increased padding for better size */
    color: #FFFFFF; /* White text color */
    text-decoration: none;
    border-radius: 5px;
    background-color: #0078d7; /* Blue background */
    font-size: 1em; /* Standard font size */
    text-align: center; /* Centered text */
    border:none;
}

.back-link:hover {
    background-color: #005bb5; /* Darker blue on hover */
    color: #FFFFFF; /* Ensure the font stays white even on hover */
    cursor:pointer;
}

    </style>
</head>
<body>

    <div class="container">
        <div class="header">Search for Food Items</div>

        <!-- Search Form -->
        <div class="search-form">
            <form method="POST" action="">
                <label for="search">Search Food:</label>
                <input type="text" id="search" name="search" required>
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Search Results -->
        <div class="results-section">
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($results)): ?>
                <h2>Search Results:</h2>
                <ul>
                    <?php foreach ($results as $row): ?>
                        <li>
                            <div>
                                <?= htmlspecialchars($row['foodname']) ?> - Calories: <?= htmlspecialchars($row['calories']) ?> kcal
                            </div>
                            <div class="action-buttons">
                                <a href="view_nutrition.php?foodid=<?= $row['foodid'] ?>">View Nutritional Info</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                <p>No food items found.</p>
            <?php endif; ?>
            <!-- Corrected Button for Adding Custom Recipe -->
<button class="back-link" onclick="window.location.href='addfood.html';">Add a custom recipe/Food</button>
<button class="back-link" onclick="window.location.href='fooddiaryrev.php';">Back to Food Diary</button>


        </div>

    </div>

</body>
</html>

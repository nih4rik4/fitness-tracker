<?php
session_start();  
$servername = "localhost";
$port = "3306";
$username = "root";
$password = "";
$dbname = "fitnesstracker";

$pdo = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];  
} else {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT dob FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $dob = new DateTime($user['dob']);
    $today = new DateTime();
    $age = $dob->diff($today)->y;
} else {
    echo "User not found or age not available.";
    exit();
}

if ($age >= 18 && $age <= 30) {
    $daily_goals = [
        'calories' => 2500.00,
        'carbs' => 300.00,
        'fat' => 70.00,
        'protein' => 120.00,
        'sodium' => 2300.00,
        'sugar' => 50.00
    ];
} elseif ($age >= 31 && $age <= 50) {
    $daily_goals = [
        'calories' => 2200.00,
        'carbs' => 250.00,
        'fat' => 60.00,
        'protein' => 100.00,
        'sodium' => 2300.00,
        'sugar' => 50.00
    ];
} elseif ($age >= 51 && $age <= 70) {
    $daily_goals = [
        'calories' => 2000.00,
        'carbs' => 220.00,
        'fat' => 50.00,
        'protein' => 90.00,
        'sodium' => 2300.00,
        'sugar' => 40.00
    ];
} else {
    $daily_goals = [
        'calories' => 1800.00,
        'carbs' => 200.00,
        'fat' => 40.00,
        'protein' => 80.00,
        'sodium' => 2300.00,
        'sugar' => 30.00
    ];
}

$mealTypes = ['breakfast', 'lunch', 'dinner', 'snacks'];
$foodDiary = [];
$total_calories = $total_carbs = $total_fat = $total_protein = $total_sodium = $total_sugar = 0;

foreach ($mealTypes as $mealType) {
    $stmt = $pdo->prepare("
        SELECT * FROM FOOD_INTAKE_DIARY 
        WHERE user_id = :user_id AND meal_type = :mealtype AND intake_date = :date
    ");
    $currentDate = date('Y-m-d');  

    $stmt->execute(['user_id' => $user_id, 'mealtype' => $mealType, 'date' => $currentDate]);
    $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $foodDiary[$mealType] = $entries;

    foreach ($entries as $entry) {
        $total_calories += $entry['total_calories'];
        $total_carbs += $entry['total_carbs'];
        $total_fat += $entry['total_fat'];
        $total_protein += $entry['total_protein'];
        $total_sodium += $entry['sodium'];
        $total_sugar += $entry['sugars'];
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Dairy</title>
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

        .date-picker {
            display: flex;
            align-items: center;
            font-size: 1.1em;
            margin-bottom: 20px;
        }

        .date-picker input[type="date"] {
            margin-left: 10px;
            padding: 5px;
            font-size: 1em;
        }

        .meal-section {
            margin-top: 15px;
        }

        .meal-section h3 {
            font-size: 1.2em;
            color: #2a5078;
        }

        .add-food {
            color: #0078d7;
            cursor: pointer;
            text-decoration: underline;
            margin-right: 10px;
        }

        .meal-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            text-align: center;
        }

        .meal-table th, .meal-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .totals-section {
            margin-top: 20px;
            width: 100%;
        }

        .totals-section table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .totals-section th, .totals-section td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .complete-entry {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 10px;
        }

        .complete-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }
        .pie_chart {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pie_chart_button {
            padding: 10px 20px;
            background-color: #0078d7;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            padding: 10px 0;
            z-index: 1;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .dropdown-content a {
            color: #0078d7;
            padding: 8px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .delete-btn {
          background-color: #FF5C5C; /* Bright red color */
    border: none;
    color: white;
    font-weight: bold; /* Bold text */
    font-size: 20px; /* Larger font for visibility */
    line-height: 1;
    padding: 5px 10px;
    border-radius: 50%; /* Makes the button round */
    cursor: pointer;
    transition: background-color 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px; /* Fixed width */
    height: 24px;

}
.delete-btn{
    text-align: center; /* Horizontally center */
    vertical-align: middle; /* Vertically center */
}

.delete-btn:hover {
    background-color: #FF3333; /* Darker red on hover */
}

.delete-btn:active {
    background-color: #CC0000; /* Even darker red when clicked */
}

.delete-btn:focus {
    outline: none; /* Remove focus outline */
}

    </style>
</head>

<body>
<div class="container">
        <div class="header">Your Food Diary For: <span id="display-date"></span></div>
        <div class="date-picker">
            <label for="food-diary-date">Date:</label>
            <input type="date" id="currentDate" name="currentDate"  onchange="updateDisplayDate()" value="<?php echo date('Y-m-d'); ?>">
        </div>

        <!-- Meal Sections -->
        <div class="meal-section">
            <h3>Breakfast</h3>
            <span class="add-food" onclick="openFoodSearch('breakfast-table')" >Add Food</span>
            <!-- <div class="dropdown">
                <span class="add-food" onclick="toggleDropdown(this)">Quick Tools</span>
                <div class="dropdown-content">
                    <a href="#">Remember meal</a>
                    <a href="#">Copy yesterday</a>
                    <a href="#">Copy from date</a>
                </div>
            </div>-->
            <table id="breakfast-table" class="meal-table">
                <thead>
                    <tr>
                        <th>Food Item</th>
                        <th>Calories kcal</th>
                        <th>Carbs g</th>
                        <th>Fat g</th>
                        <th>Protein g</th>
                        <th>Sodium mg</th>
                        <th>Sugar g</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($foodDiary['breakfast'] as $entry): ?>
                    <tr data-id="<?= $entry['id'] ?>">

                        <td><?= htmlspecialchars($entry['foodname']) ?></td>
                        <td><?= htmlspecialchars($entry['total_calories']) ?></td>
                        <td><?= htmlspecialchars($entry['total_carbs']) ?></td>
                        <td><?= htmlspecialchars($entry['total_fat']) ?></td>
                        <td><?= htmlspecialchars($entry['total_protein']) ?></td>
                        <td><?= htmlspecialchars($entry['sodium']) ?></td>
                        <td><?= htmlspecialchars($entry['sugars']) ?></td>
                        <td><button class="delete-btn" onclick="deleteRow(this)">−</button></td>

                    </tr>
                <?php endforeach; ?>
                
                </tbody>
            </table>
        </div>

        <div class="meal-section">
            <h3>Lunch</h3>
            <span class="add-food" onclick="openFoodSearch('lunch-table')">Add Food</span>
          
            <table id="lunch-table" class="meal-table">
                <thead>
                    <tr>
                        <th>Food Item</th>
                        <th>Calories kcal</th>
                        <th>Carbs g</th>
                        <th>Fat g</th>
                        <th>Protein g</th>
                        <th>Sodium mg</th>
                        <th>Sugar g</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($foodDiary['lunch'] as $entry): ?>
                    <tr data-id="<?= $entry['id'] ?>">

        <td><?= htmlspecialchars($entry['foodname']) ?></td>
        <td><?= htmlspecialchars($entry['total_calories']) ?></td>
        <td><?= htmlspecialchars($entry['total_carbs']) ?></td>
        <td><?= htmlspecialchars($entry['total_fat']) ?></td>
        <td><?= htmlspecialchars($entry['total_protein']) ?></td>
        <td><?= htmlspecialchars($entry['sodium']) ?></td>
        <td><?= htmlspecialchars($entry['sugars']) ?></td>
        <td><button class="delete-btn" onclick="deleteRow(this)">-</button></td>

    </tr>
<?php endforeach; ?>

                </tbody>
            </table>
        </div>

        <div class="meal-section">
            <h3>Dinner</h3>
            <span class="add-food" onclick="openFoodSearch('dinner-table')">Add Food</span>
           
            <table id="dinner-table" class="meal-table">
                <thead>
                    <tr>
                        <th>Food Item</th>
                        <th>Calories kcal</th>
                        <th>Carbs g</th>
                        <th>Fat g</th>
                        <th>Protein g</th>
                        <th>Sodium mg</th>
                        <th>Sugar g</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($foodDiary['dinner'] as $entry): ?>
                    <tr data-id="<?= $entry['id'] ?>">

        <td><?= htmlspecialchars($entry['foodname']) ?></td>
        <td><?= htmlspecialchars($entry['total_calories']) ?></td>
        <td><?= htmlspecialchars($entry['total_carbs']) ?></td>
        <td><?= htmlspecialchars($entry['total_fat']) ?></td>
        <td><?= htmlspecialchars($entry['total_protein']) ?></td>
        <td><?= htmlspecialchars($entry['sodium']) ?></td>
        <td><?= htmlspecialchars($entry['sugars']) ?></td>
        <td><button class="delete-btn" onclick="deleteRow(this)">-</button></td>


    </tr>
                </tbody>
    
<?php endforeach; ?>

            </table>
        </div>

        <div class="meal-section">
            <h3>Snacks</h3>
            <span class="add-food" onclick="openFoodSearch('snacks-table')">Add Food</span>
           
            <table id="snacks-table" class="meal-table">
                <thead>
                    <tr>
                        <th>Food Item</th>
                        <th>Calories kcal</th>
                        <th>Carbs g</th>
                        <th>Fat g</th>
                        <th>Protein g</th>
                        <th>Sodium mg</th>
                        <th>Sugar g</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($foodDiary['snacks'] as $entry): ?>
    <tr data-id="<?= $entry['id'] ?>">

        <td><?= htmlspecialchars($entry['foodname']) ?></td>
        <td><?= htmlspecialchars($entry['total_calories']) ?></td>
        <td><?= htmlspecialchars($entry['total_carbs']) ?></td>
        <td><?= htmlspecialchars($entry['total_fat']) ?></td>
        <td><?= htmlspecialchars($entry['total_protein']) ?></td>
        <td><?= htmlspecialchars($entry['sodium']) ?></td>
        <td><?= htmlspecialchars($entry['sugars']) ?></td>
        <td><button class="delete-btn" onclick="deleteRow(this)">-</button></td>

    </tr>
<?php endforeach; ?>

                </tbody>
            </table>
        </div>

        <div class="totals-section">
    <table>
        <thead>
            <tr>
                <th>Totals</th>
                <th id="total-calories"><?php echo $total_calories; ?></th>
                <th id="total-carbs"><?php echo $total_carbs; ?></th>
                <th id="total-fat"><?php echo $total_fat; ?></th>
                <th id="total-protein"><?php echo $total_protein; ?></th>
                <th id="total-sodium"><?php echo $total_sodium; ?></th>
                <th id="total-sugar"><?php echo $total_sugar; ?></th>
            </tr>
            <tr>
                <th>Your Daily Goal</th>
                <th><?php echo $daily_goals['calories']; ?></th>
                <th><?php echo $daily_goals['carbs']; ?></th>
                <th><?php echo $daily_goals['fat']; ?></th>
                <th><?php echo $daily_goals['protein']; ?></th>
                <th><?php echo $daily_goals['sodium']; ?></th>
                <th><?php echo $daily_goals['sugar']; ?></th>
            </tr>
            <tr>
                <th>Remaining</th>
                <th id="remaining-calories"><?php echo $daily_goals['calories'] - $total_calories; ?></th>
                <th id="remaining-carbs"><?php echo $daily_goals['carbs'] - $total_carbs; ?></th>
                <th id="remaining-fat"><?php echo $daily_goals['fat'] - $total_fat; ?></th>
                <th id="remaining-protein"><?php echo $daily_goals['protein'] - $total_protein; ?></th>
                <th id="remaining-sodium"><?php echo $daily_goals['sodium'] - $total_sodium; ?></th>
                <th id="remaining-sugar"><?php echo $daily_goals['sugar'] - $total_sugar; ?></th>
            </tr>
        </thead>
    </table>
</div>
        <div class="pie_chart">
            <button class="pie_chart_button" onclick="window.location.href='pie_chart.php';">view macronutrients distribution</button>
        </div>
        <!-- Complete Entry -->
        <div class="complete-entry">
            <button class="complete-button " onclick="completeEntry()">Complete Entry</button> 
            <button class="complete-button " onclick="window.location.href='homepage.html';">Back to Home Page</button>

                </div>

    <script>
          document.addEventListener("DOMContentLoaded", function() {
        const dateInput = document.getElementById("currentDate");
        const today = new Date().toISOString().split("T")[0]; // Get current date in 'YYYY-MM-DD' format
        dateInput.value = today; // Set input's value to today's date
        updateDisplayDate(); // Call function to update the displayed date if needed
    });
   
    function updateDisplayDate() {
    const datePicker = document.getElementById("currentDate"); // Corrected to "currentDate"
    const displayDate = document.getElementById("display-date");
    const selectedDate = datePicker.value;
    displayDate.innerText = new Date(selectedDate).toLocaleDateString("en-US", {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });
    fetchFoodEntries(selectedDate); // Fetch entries for the selected date
}


function fetchFoodEntries(date) {
        fetch('getFoodEntries.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ date: date })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                displayFoodEntries(data.entries);
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error fetching entries:', error));
    }

    function displayFoodEntries(entries) {
        // Logic to display entries in tables
        // Clear current tables and populate with new data from 'entries'
    }

        function toggleDropdown(element) {
            var dropdown = element.nextElementSibling;
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        function openFoodSearch(tableId) {
          window.location.href = 'search_food.php'; 
          // You can replace this with your actual search functionality
        }

        // Function to update the totals and remaining values
function updateTotals() {
    // Get all the rows from each meal table
    const mealTypes = ['breakfast', 'lunch', 'dinner', 'snacks'];
    let totalCalories = 0;
    let totalCarbs = 0;
    let totalFat = 0;
    let totalProtein = 0;
    let totalSodium = 0;
    let totalSugar = 0;

    // Loop through each meal type and sum the values
    mealTypes.forEach(function(meal) {
        const table = document.getElementById(meal + '-table');
        const rows = table.getElementsByTagName('tr');
        
        // Skip the first row (header row)
        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            totalCalories += parseFloat(cells[1].innerText) || 0;
            totalCarbs += parseFloat(cells[2].innerText) || 0;
            totalFat += parseFloat(cells[3].innerText) || 0;
            totalProtein += parseFloat(cells[4].innerText) || 0;
            totalSodium += parseFloat(cells[5].innerText) || 0;
            totalSugar += parseFloat(cells[6].innerText) || 0;
        }
    });

    // Update totals in the table
    document.getElementById("total-calories").innerText = totalCalories.toFixed(2);
    document.getElementById("total-carbs").innerText = totalCarbs.toFixed(2);
    document.getElementById("total-fat").innerText = totalFat.toFixed(2);
    document.getElementById("total-protein").innerText = totalProtein.toFixed(2);
    document.getElementById("total-sodium").innerText = totalSodium.toFixed(2);
    document.getElementById("total-sugar").innerText = totalSugar.toFixed(2);

    // Set daily goal values (static as per your code)
    // const dailyGoals = {
    //     calories: 1580,
    //     carbs: 198,
    //     fat: 53,
    //     protein: 80,
    //     sodium: 2300,
    //     sugar: 59
    // };

    // Update remaining values
    document.getElementById("remaining-calories").innerText = (dailyGoals.calories - totalCalories).toFixed(2);
    document.getElementById("remaining-carbs").innerText = (dailyGoals.carbs - totalCarbs).toFixed(2);
    document.getElementById("remaining-fat").innerText = (dailyGoals.fat - totalFat).toFixed(2);
    document.getElementById("remaining-protein").innerText = (dailyGoals.protein - totalProtein).toFixed(2);
    document.getElementById("remaining-sodium").innerText = (dailyGoals.sodium - totalSodium).toFixed(2);
    document.getElementById("remaining-sugar").innerText = (dailyGoals.sugar - totalSugar).toFixed(2);
}

// Call updateTotals function on page load
window.onload = updateTotals;

function deleteRow(button) {
    // Get the row and the ID from the data-id attribute
    const row = button.closest('tr');
    const foodIntakeId = row.getAttribute('data-id');

    // Send an AJAX request to delete the entry from the database
    fetch('delete_food.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'id=' + encodeURIComponent(foodIntakeId)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Remove the row from the table
            row.remove();
            // Update totals and remaining values after deletion
            updateTotals();
        } else {
            alert('Error deleting food item: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the item.');
    });
}


function completeEntry() {
    // Get all the meal data
    const mealTypes = ['breakfast', 'lunch', 'dinner', 'snacks'];
    const foodData = {};

    mealTypes.forEach(function(meal) {
        const table = document.getElementById(meal + '-table');
        const rows = table.getElementsByTagName('tr');
        const mealItems = [];

        // Loop through each row and get the data
        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            const food = {
                foodname: cells[0].innerText,
                total_calories: parseFloat(cells[1].innerText) || 0,
                total_carbs: parseFloat(cells[2].innerText) || 0,
                total_fat: parseFloat(cells[3].innerText) || 0,
                total_protein: parseFloat(cells[4].innerText) || 0,
                sodium: parseFloat(cells[5].innerText) || 0,
                sugars: parseFloat(cells[6].innerText) || 0
            };
            mealItems.push(food);
        }

        // Store the meal data
        if (mealItems.length > 0) {
            foodData[meal] = mealItems;
        }
    });

    // Disable the ability to edit or add more food
    document.querySelectorAll('.add-food, .delete-btn').forEach(function(button) {
        button.style.display = 'none'; // Hide add/delete buttons
    });

    // Disable the date input
    document.getElementById('food-diary-date').disabled = true;

    // Send data to the server to save the food diary
    fetch('save_food_diary.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(foodData) // Send meal data as JSON
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Your food diary has been saved!');
            // Optionally redirect to a confirmation page or hide the "Complete Entry" button
            document.querySelector('.complete-entry').style.display = 'none'; // Hide complete button
        } else {
            alert('An error occurred while saving your diary.');
        }
    })
    .catch(error => {
        console.error('Error saving food diary:', error);
        alert('An error occurred while saving your diary.');
    });
}


// Also update totals when the date is changed
function updateDisplayDate() {
    const datePicker = document.getElementById("currentDate");
    const displayDate = document.getElementById("display-date");
    displayDate.innerText = new Date(datePicker.value).toLocaleDateString("en-US", { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

    // Recalculate totals when the date is changed
    updateTotals();


}

    </script>
</body>
</html>
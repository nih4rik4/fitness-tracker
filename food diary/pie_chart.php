<?php
session_start();  // Make sure the session is started
$servername = "localhost";
$port = "3306";
$username = "root";
$password = "";
$dbname = "fitnesstracker";

// Set up the PDO connection
$pdo = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if user_id is set in the session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];  // Retrieve user_id from the session
} else {
    // Handle the case where user_id is not set in the session
    // You might want to redirect the user to the login page
    header("Location: login.php");
    exit();
}

// Define meal types
$mealTypes = ['breakfast', 'lunch', 'dinner', 'snacks'];
$foodDiary = [];

// Fetch food diary entries for each meal type
foreach ($mealTypes as $mealType) {
    $stmt = $pdo->prepare("
        SELECT * FROM FOOD_INTAKE_DIARY 
        WHERE user_id = :user_id AND meal_type = :mealtype AND intake_date = :date
    ");
    $currentDate = date('Y-m-d');  // Current date in 'YYYY-MM-DD' format

    $stmt->execute(['user_id' => $user_id, 'mealtype' => $mealType, 'date' => $currentDate]);
    $foodDiary[$mealType] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calorie Calculator</title>
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
        .container2{
            width: 60%;
            margin: 20px auto;
            background-color: white;
            /* border: 1px solid #ddd; */
            /* border-radius: 5px; */
            padding: 20px;
            /* box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); */

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
            gap:10px;
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


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
                        <!-- <td><button class="delete-btn" onclick="deleteRow(this)">−</button></td> -->

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
        <!-- <td><button class="delete-btn" onclick="deleteRow(this)">-</button></td> -->

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
        <!-- <td><button class="delete-btn" onclick="deleteRow(this)">-</button></td> -->


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
        <!-- <td><button class="delete-btn" onclick="deleteRow(this)">-</button></td> -->

    </tr>
<?php endforeach; ?>

                </tbody>
            </table>
        </div>

        <!-- Total Section -->
         <!-- Totals Section -->
         <div class="totals-section">
            <table>
                <thead>
                    <tr>
                        <th>Totals</th>
                        <th id="total-calories">0</th>
                        <th id="total-carbs">0</th>
                        <th id="total-fat">0</th>
                        <th id="total-protein">0</th>
                        <th id="total-sodium">0</th>
                        <th id="total-sugar">0</th>
                    </tr>
                    <tr>
                        <th>Your Daily Goal</th>
                        <th>1580</th>
                        <th>198</th>
                        <th>53</th>
                        <th>80</th>
                        <th>2300</th>
                        <th>59</th>
                    </tr>
                    <tr>
                        <th>Remaining</th>
                        <th id="remaining-calories">1580</th>
                        <th id="remaining-carbs">198</th>
                        <th id="remaining-fat">53</th>
                        <th id="remaining-protein">80</th>
                        <th id="remaining-sodium">2300</th>
                        <th id="remaining-sugar">59</th>
                    </tr>
                </thead>
            </table>
        </div>

       <div class="container2">
    <canvas id="totalsPieChart" width="0" height="0"></canvas>
    </div>
<script>
function updateDisplayDate() {
    const datePicker = document.getElementById("food-diary-date");
    const displayDate = document.getElementById("display-date");
    displayDate.innerText = new Date(datePicker.value).toLocaleDateString("en-US", { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });



    // Recalculate totals when the date is changed
    updateTotals();
    renderPieChart(); // Ensure the chart updates when the date changes
}

function toggleDropdown(element) {
    var dropdown = element.nextElementSibling;
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

function openFoodSearch(tableId) {
    window.location.href = 'search_food.php'; 
}

function updateTotals() {
    const mealTypes = ['breakfast', 'lunch', 'dinner', 'snacks'];
    let totalCalories = 0;
    let totalCarbs = 0;
    let totalFat = 0;
    let totalProtein = 0;
    let totalSodium = 0;
    let totalSugar = 0;

    mealTypes.forEach(function(meal) {
        const table = document.getElementById(meal + '-table');
        const rows = table.getElementsByTagName('tr');

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

    document.getElementById("total-calories").innerText = totalCalories.toFixed(2);
    document.getElementById("total-carbs").innerText = totalCarbs.toFixed(2);
    document.getElementById("total-fat").innerText = totalFat.toFixed(2);
    document.getElementById("total-protein").innerText = totalProtein.toFixed(2);
    document.getElementById("total-sodium").innerText = totalSodium.toFixed(2);
    document.getElementById("total-sugar").innerText = totalSugar.toFixed(2);

    const dailyGoals = {
        calories: 1580,
        carbs: 198,
        fat: 53,
        protein: 80,
        sodium: 2300,
        sugar: 59
    };

    document.getElementById("remaining-calories").innerText = (dailyGoals.calories - totalCalories).toFixed(2);
    document.getElementById("remaining-carbs").innerText = (dailyGoals.carbs - totalCarbs).toFixed(2);
    document.getElementById("remaining-fat").innerText = (dailyGoals.fat - totalFat).toFixed(2);
    document.getElementById("remaining-protein").innerText = (dailyGoals.protein - totalProtein).toFixed(2);
    document.getElementById("remaining-sodium").innerText = (dailyGoals.sodium - totalSodium).toFixed(2);
    document.getElementById("remaining-sugar").innerText = (dailyGoals.sugar - totalSugar).toFixed(2);
}

function deleteRow(button) {
    var row = button.parentNode.parentNode;
    row.parentNode.removeChild(row);
    updateTotals();
}

function renderPieChart() {
    // const totalCalories = parseFloat(document.getElementById("total-calories").innerText) || 0;
    const totalCarbs = parseFloat(document.getElementById("total-carbs").innerText) || 0;
    const totalFat = parseFloat(document.getElementById("total-fat").innerText) || 0;
    const totalProtein = parseFloat(document.getElementById("total-protein").innerText) || 0;
    const totalSodium = parseFloat(document.getElementById("total-sodium").innerText) || 0;
    const totalSugar = parseFloat(document.getElementById("total-sugar").innerText) || 0;

    const data = {
        labels: [ 'Carbs (g)', 'Fat (g)', 'Protein (g)', 'Sodium (mg)', 'Sugar (g)'],
        datasets: [{
            label: 'Nutritional Breakdown',
            data: [totalCalories, totalCarbs, totalFat, totalProtein, totalSodium, totalSugar],
            backgroundColor: [
                'rgba(255, 99, 132, 0.6)', 
                'rgba(54, 162, 235, 0.6)', 
                'rgba(255, 206, 86, 0.6)', 
                // 'rgba(75, 192, 192, 0.6)', 
                'rgba(153, 102, 255, 0.6)', 
                'rgba(255, 159, 64, 0.6)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                // 'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    };

    const config = {
        type: 'pie',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2);
                        }
                    }
                }
            }
        }
    };

    const ctx = document.getElementById('totalsPieChart').getContext('2d');
    new Chart(ctx, config);
}

window.onload = function() {
    updateTotals();
    renderPieChart();
};
// window.onload = function() {
//     updateTotals();
//     renderPieChart();
// };

// Update chart whenever the date is changed
document.getElementById('food-diary-date').addEventListener('change', function() {
    updateTotals();
    renderPieChart();
});

</script>


       <!-- Complete Entry -->
    <div class="complete-entry">
        <button class="complete-button" onclick="completeEntry()">Complete Entry</button>
        <button class="complete-button" onclick="window.location.href='fooddiaryrev.php'">Edit</button>
        <button class="complete-button " onclick="window.location.href='homepage.html';">Back to Home Page</button>

    </div>

    <!-- Include your canvas for the pie chart -->
    <canvas id="totalsPieChart" width="400" height="400"></canvas>

    <script>
    // function updateDisplayDate() {
    //     const datePicker = document.getElementById("food-diary-date");
    //     const displayDate = document.getElementById("display-date");
    //     displayDate.innerText = new Date(datePicker.value).toLocaleDateString("en-US", { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
 function updateDisplayDate() {
    const datePicker = document.getElementById("currentDate"); // Corrected to "currentDate"
    const displayDate = document.getElementById("display-date");
    const selectedDate = datePicker.value;
    displayDate.innerText = new Date(selectedDate).toLocaleDateString("en-US", {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });
        // Recalculate totals when the date is changed
        updateTotals();
        renderPieChart(); // Ensure the chart updates when the date changes
    }

    function toggleDropdown(element) {
        const dropdown = element.nextElementSibling;
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    function openFoodSearch() {
        window.location.href = 'search_food.php'; 
    }

    function updateTotals() {
    const mealTypes = ['breakfast', 'lunch', 'dinner', 'snacks'];
    let totalCalories = 0;
    let totalCarbs = 0;
    let totalFat = 0;
    let totalProtein = 0;
    let totalSodium = 0;
    let totalSugar = 0;

    mealTypes.forEach(function(meal) {
        const table = document.getElementById(meal + '-table');
        const rows = table.getElementsByTagName('tr');

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

    // Update totals display
    document.getElementById("total-calories").innerText = totalCalories.toFixed(2);
    document.getElementById("total-carbs").innerText = totalCarbs.toFixed(2);
    document.getElementById("total-fat").innerText = totalFat.toFixed(2);
    document.getElementById("total-protein").innerText = totalProtein.toFixed(2);
    document.getElementById("total-sodium").innerText = totalSodium.toFixed(2);
    document.getElementById("total-sugar").innerText = totalSugar.toFixed(2);

    // Daily goals
    const dailyGoals = {
        calories: 1580,
        carbs: 198,
        fat: 53,
        protein: 80,
        sodium: 2300,
        sugar: 59
    };

    // Update remaining values
    document.getElementById("remaining-calories").innerText = (dailyGoals.calories - totalCalories).toFixed(2);
    document.getElementById("remaining-carbs").innerText = (dailyGoals.carbs - totalCarbs).toFixed(2);
    document.getElementById("remaining-fat").innerText = (dailyGoals.fat - totalFat).toFixed(2);
    document.getElementById("remaining-protein").innerText = (dailyGoals.protein - totalProtein).toFixed(2);
    document.getElementById("remaining-sodium").innerText = (dailyGoals.sodium - totalSodium).toFixed(2);
    document.getElementById("remaining-sugar").innerText = (dailyGoals.sugar - totalSugar).toFixed(2);
}


function renderPieChart() {
    // Only include carbs, fat, and protein in the pie chart
    const totalCarbs = parseFloat(document.getElementById("total-carbs").innerText) || 0;
    const totalFat = parseFloat(document.getElementById("total-fat").innerText) || 0;
    const totalProtein = parseFloat(document.getElementById("total-protein").innerText) || 0;

    const data = {
        labels: ['Carbs (g)', 'Fat (g)', 'Protein (g)'],
        datasets: [{
            label: 'Nutritional Breakdown',
            data: [totalCarbs, totalFat, totalProtein],
            backgroundColor: [
                'rgba(54, 162, 235, 0.6)', 
                'rgba(255, 206, 86, 0.6)', 
                'rgba(75, 192, 192, 0.6)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
        }]
    };

    const config = {
        type: 'pie',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2);
                        }
                    }
                }
            }
        }
    };

    const ctx = document.getElementById('totalsPieChart').getContext('2d');
    new Chart(ctx, config);
}


    function completeEntry() {
        alert('Your food diary has been saved!');
    }
    </script>

</body>
</html>
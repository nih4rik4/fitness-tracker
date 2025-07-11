<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page
    header('Location: login.html');
    exit;
}

// If the user is logged in, continue with the food diary page logic
$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];


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
    </style>
   
</head>





<body>
    <div class="container">
        <div class="header">Your Food Diary For: <span id="display-date"></span></div>
        <div class="date-picker">
            <label for="food-diary-date">Date:</label>
            <input type="date" id="food-diary-date" name="food-diary-date" value="2024-11-05" onchange="updateDisplayDate()">
        </div>

        <!-- Meal Sections -->
        <div class="meal-section">
            <h3>Breakfast</h3>
            <span class="add-food" onclick="openFoodSearch('breakfast-table')" href="addfood.html">Add Food</span>|
            <div class="dropdown">
                <span class="add-food" onclick="toggleDropdown(this)">Quick Tools</span>
                <div class="dropdown-content">
                    <a href="#">Remember meal</a>
                    <a href="#">Copy yesterday</a>
                    <a href="#">Copy from date</a>
                </div>
            </div>
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
                <tbody></tbody>
            </table>
        </div>

        <div class="meal-section">
            <h3>Lunch</h3>
            <span class="add-food" onclick="openFoodSearch('lunch-table')">Add Food</span>|
            <div class="dropdown">
               <span class="add-food" onclick="toggleDropdown(this)">Quick Tools</span>
               <div class="dropdown-content">
                   <a href="#">Remember meal</a>
                   <a href="#">Copy yesterday</a>
                   <a href="#">Copy from date</a>
               </div>
           </div>
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
                <tbody></tbody>
            </table>
        </div>

        <div class="meal-section">
            <h3>Dinner</h3>
            <span class="add-food" onclick="openFoodSearch('dinner-table')">Add Food</span>|
            <div class="dropdown">
               <span class="add-food" onclick="toggleDropdown(this)">Quick Tools</span>
               <div class="dropdown-content">
                   <a href="#">Remember meal</a>
                   <a href="#">Copy yesterday</a>
                   <a href="#">Copy from date</a>
               </div>
           </div>
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
                <tbody></tbody>
            </table>
        </div>

        <div class="meal-section">
            <h3>Snacks</h3>
            <span class="add-food" onclick="openFoodSearch('snacks-table')">Add Food</span>|
            <div class="dropdown">
               <span class="add-food" onclick="toggleDropdown(this)">Quick Tools</span>
               <div class="dropdown-content">
                   <a href="#">Remember meal</a>
                   <a href="#">Copy yesterday</a>
                   <a href="#">Copy from date</a>
               </div>
           </div>
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
                <tbody></tbody>
            </table>
        </div>

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

        <!-- Complete Entry -->
        <div class="complete-entry">
            <button class="complete-button" onclick="completeEntry()">Complete Entry</button>
        </div>
    </div>

    <script>
        function openFoodSearch(mealType) {
            sessionStorage.setItem("mealType", mealType);
            window.location.href = "addfood.html";
        }

        function updateDisplayDate() {
            const datePicker = document.getElementById("food-diary-date");
            const displayDate = document.getElementById("display-date");
            displayDate.innerText = new Date(datePicker.value).toLocaleDateString("en-US", { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }

        document.addEventListener("DOMContentLoaded", function() {
            updateDisplayDate();
        });

        // Function to toggle the dropdown
        function toggleDropdown(element) {
            const dropdown = element.nextElementSibling;
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        }

        // Function to load food data
        function loadFoodData() {
            const mealTables = ["breakfast-table", "lunch-table", "dinner-table", "snacks-table"];

            mealTables.forEach(tableId => {
                const foodData = sessionStorage.getItem(tableId);

                if (foodData) {
                    const { item, calories, carbs, fat, protein, sodium, sugar } = JSON.parse(foodData);
                    sessionStorage.removeItem(tableId);

                    const tableBody = document.getElementById(tableId).querySelector("tbody");
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${item}</td>
                        <td>${calories}</td>
                        <td>${carbs}</td>
                        <td>${fat}</td>
                        <td>${protein}</td>
                        <td>${sodium}</td>
                        <td>${sugar}</td>
                    `;
                    tableBody.appendChild(row);
                }
            });
        }
        

        // Call loadFoodData when the page loads
        document.addEventListener("DOMContentLoaded", loadFoodData);
        document.addEventListener("DOMContentLoaded", function() {
            const date = document.getElementById("food-diary-date").value;
            fetch(`get_food_intake.php?date=${date}`)
                .then(response => response.json())
                .then(data => {
                    for (const meal of data) {
                        const tableBody = document.getElementById(`${meal.mealType}-table`).querySelector("tbody");
                        tableBody.innerHTML = meal.items.map(item => `
                            <tr>
                                <td>${item.foodName}</td>
                                <td>${item.calories}</td>
                                <td>${item.carbs}</td>
                                <td>${item.fat}</td>
                                <td>${item.protein}</td>
                                <td>${item.sodium}</td>
                                <td>${item.sugar}</td>
                            </tr>
                        `).join('');
                    }
                });
        });

        // function loadFoodData() {
        //     const date = document.getElementById("food-diary-date").value;

        //     fetch('food_display.php?date=${date}')
        //         .then(response => response.json())
        //         .then(data => {
        //             let totals = {
        //                 calories: 0,
        //                 carbs: 0,
        //                 fat: 0,
        //                 protein: 0,
        //                 sodium: 0,
        //                 sugars: 0
        //             };

        //             for (const meal in data) {
        //                 const tableBody = document.getElementById(`${meal}-table`).querySelector("tbody");
        //                 tableBody.innerHTML = '';  // Clear any previous data

        //                 // Populate the table with the fetched meal data
        //                 data[meal].forEach(item => {
        //                     const row = document.createElement("tr");
        //                     row.innerHTML = `
        //                         <td>${item.foodname}</td>
        //                         <td>${item.calories}</td>
        //                         <td>${item.carbs}</td>
        //                         <td>${item.fat}</td>
        //                         <td>${item.protein}</td>
        //                         <td>${item.sodium}</td>
        //                         <td>${item.sugars}</td>
        //                     `;
        //                     tableBody.appendChild(row);

        //                     // Add to total values
        //                     totals.calories += parseFloat(item.calories);
        //                     totals.carbs += parseFloat(item.carbs);
        //                     totals.fat += parseFloat(item.fat);
        //                     totals.protein += parseFloat(item.protein);
        //                     totals.sodium += parseFloat(item.sodium);
        //                     totals.sugars += parseFloat(item.sugars);
        //                 });
        //             }

        //             // Update totals in the table
        //             document.getElementById("total-calories").innerText = totals.calories.toFixed(2);
        //             document.getElementById("total-carbs").innerText = totals.carbs.toFixed(2);
        //             document.getElementById("total-fat").innerText = totals.fat.toFixed(2);
        //             document.getElementById("total-protein").innerText = totals.protein.toFixed(2);
        //             document.getElementById("total-sodium").innerText = totals.sodium.toFixed(2);
        //             document.getElementById("total-sugar").innerText = totals.sugars.toFixed(2);
        //         })
        //         .catch(error => console.error("Error fetching food intake data:", error));
        // }

        function completeEntry() {
            alert("Entry Completed!");
        }

// Function to update the display date when the date picker is changed
function updateDisplayDate() {
    const datePicker = document.getElementById("food-diary-date");
    const displayDate = document.getElementById("display-date");
    displayDate.innerText = new Date(datePicker.value).toLocaleDateString("en-US", { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
}

    </script>
</body>
</html>


# 🏋️‍♂ Fitness Tracker (PHP + MySQL)

A complete fitness tracking web application built with PHP and MySQL, designed to help users stay healthy, track workouts, monitor nutrition, and stay motivated with personalized recommendations.  
Runs locally on the XAMPP server for development and testing.

---

##  Features

-  User registration and login
-  Log daily workouts with sets, reps, duration, and intensity
-  Set personal fitness goals (weight loss, muscle gain, endurance, etc.)
-  Track nutrition and calorie intake
-  Log daily water consumption
-  Food intake logging with macro and calorie breakdown
-  Personalized **music** recommendations for workouts
-  Personalized **food** suggestions based on user goals
-  View historical data and workout summaries
-  Dashboard with graphs and charts
-  Secure session-based authentication
-  All data stored in MySQL database
-  Responsive frontend with HTML, CSS, and JavaScript

---

##  Tech Stack

- **Frontend:** HTML, CSS, JavaScript  
- **Backend:** PHP  
- **Database:** MySQL (via phpMyAdmin)  
- **Server:** Apache (via XAMPP)

---

##  How to Run Locally

###  Prerequisites

- [XAMPP](https://www.apachefriends.org/) installed on your system

###  Setup Instructions

1. **Clone the Repository**
   ```bash
   git clone https://github.com/nih4rik4/fitness-tracker.git

2. Move the Project Folder

 <pre> C:\xampp\htdocs\fitness-tracker </pre>

3.Start XAMPP
 -Launch Apache and MySQL
 -Set Up the Database
 -Open http://localhost/phpmyadmin

4.Create a new database named:
 <pre> fitnesstracker_db </pre>

  -Go to the Import tab
 -Select the file: sql/fitness_db.sql from the project
 -Click Go to import tables and sample data
 
 5.Update Database Config
 -In config.php, check your connection settings:

 <pre>  $host = "localhost";
 $username = "root";
 $password = "";
 $database = "fitnesstracker_db";  </pre>

6.Run the Application
-Open your browser and go to:
 <pre>http://localhost/fitness-tracker/ </pre>


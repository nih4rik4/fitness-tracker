<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.php");
    exit();
}

$servername = "localhost";
$port = "3306";
$username = "root";
$password = "";
$dbname = "fitnesstracker";

try {
    $pdo = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the total number of users
    $query = "SELECT COUNT(*) AS total_users FROM users";
    $stmt = $pdo->query($query);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_users = $result['total_users'];

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: black;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }

        .dashboard-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 50px auto;
            max-width: 800px;
            gap: 40px;
        }

        .info-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .circle-container {
            text-align: center;
        }

        .circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background-color: #28a745;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            animation: pulse 1.5s ease-in-out infinite;
            margin: 0 auto;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }
        }

        .description {
            font-size: 18px;
            color: #333;
            text-align: center;
            margin-top: 10px;
        }

        .add-food-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }

        .add-food-btn:hover {
            background-color: #218838;
        }

        .card {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            margin-top: 30px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        Admin Dashboard
    </div>

    <div class="dashboard-container">
        <div class="info-container">
            <div class="circle-container">
                <div class="circle">
                    <?php echo $total_users; ?>
                </div>
                <p class="description">Total Number of Users</p>
            </div>

            <div class="card">
                <h2>Welcome, <?php echo $_SESSION['admin_username']; ?>!</h2>
                <p>Manage your application efficiently from here.</p>
            </div>
        </div>

        <button class="add-food-btn" onclick="window.location.href='foodadmin.php'">
            Add New Food Item
        </button>
    </div>
</body>
</html>

<?php
$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$mealType = isset($_GET['mealtype']) ? $_GET['mealtype'] : 'breakfast';
$currentDate = date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT * FROM FOOD_INTAKE_DIARY 
    WHERE user_id = 1 AND meal_type = :mealtype AND intake_date = :date
");
$stmt->execute(['mealtype' => $mealType, 'date' => $currentDate]);

$foodDiary = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<table>
    <thead>
        <tr>
            <th>Food Name</th>
            <th>Serving Size</th>
            <th>Quantity</th>
            <th>Calories</th>
            <th>Carbs</th>
            <th>Fat</th>
            <th>Protein</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($foodDiary as $entry): ?>
            <tr>
                <td><?= htmlspecialchars($entry['foodname']) ?></td>
                <td><?= htmlspecialchars($entry['serving_size']) ?></td>
                <td><?= htmlspecialchars($entry['quantity']) ?></td>
                <td><?= htmlspecialchars($entry['total_calories']) ?></td>
                <td><?= htmlspecialchars($entry['total_carbs']) ?></td>
                <td><?= htmlspecialchars($entry['total_fat']) ?></td>
                <td><?= htmlspecialchars($entry['total_protein']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

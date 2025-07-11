<?php
// Start the PHP session to access session variables
session_start();

// Retrieve user_id from the session, not from the request body
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];  // Retrieve user_id from the session
} else {
    // If the user is not logged in, return an error
    echo json_encode(['success' => false, 'error' => ['User not logged in']]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Goal Types</title>
  <link rel="stylesheet" href="gstyles.css">
</head>
<body>
  <div class="shadow-container">
    <div class="goal-types">
      <h1>Goal Types</h1>
      <p>Select only 1 goal:</p>
      <div class="button-container" id="goalButtons"></div>
      <div class="target-inputs" id="targetInputs"></div>
      <div class="nav-buttons">
        <button onclick="history.back()" class="nav-button">Back</button>
        <button onclick="handleSubmit()" class="nav-button">Next</button>
      </div>
    </div>
  </div>

  <script>
    const goalTypes = ["Gain Weight", "Lose Weight", "Maintain Weight", "Manage Stress", "Improve Endurance"];
    let selectedGoal = null;
    let userId = <?php echo json_encode($user_id); ?>;  // Directly passing the PHP session user_id to JavaScript
  
    // Function to handle goal button click
    function handleGoalClick(goal) {
      selectedGoal = selectedGoal === goal ? null : goal;
      renderGoalButtons();
      renderTargetInputs();
    }
  
    // Function to render the goal buttons
    function renderGoalButtons() {
      const goalButtonsContainer = document.getElementById('goalButtons');
      goalButtonsContainer.innerHTML = goalTypes.map(goal => {
        return `<button onclick="handleGoalClick('${goal}')" class="custom-button ${selectedGoal === goal ? 'selected' : ''}">${goal}</button>`;
      }).join('');
    }
  
    // Function to render the target input based on the selected goal
    function renderTargetInputs() {
      const targetInputsContainer = document.getElementById('targetInputs');
      if (selectedGoal && ["Gain Weight", "Lose Weight", "Maintain Weight"].includes(selectedGoal)) {
        targetInputsContainer.innerHTML = `
          <label for="target_${selectedGoal}">${selectedGoal} Target Value: 
            <input type="number" placeholder="Enter target value" id="target_${selectedGoal}" required>
          </label>
        `;
      } else {
        targetInputsContainer.innerHTML = '';
      }
    }
  
    // Function to handle form submission
    async function handleSubmit() {
      if (!userId) {
        alert('User is not logged in. Please sign in first.');
        return;
      }

      if (selectedGoal) {
        let targetValue = '';  // Set default targetValue as an empty string
        
        // For Gain Weight or Lose Weight, check if target value is provided
        if (["Gain Weight", "Lose Weight"].includes(selectedGoal)) {
          const targetInput = document.getElementById(`target_${selectedGoal}`);
          targetValue = targetInput ? targetInput.value.trim() : '';

          if (!targetValue) {
            alert('Please enter a target value for your goal.');
            targetInput.focus();
            return;
          }
        }

        // Goal ID is the index in the goalTypes array + 1
        const goalId = goalTypes.indexOf(selectedGoal) + 1;

        // Send data to the backend (insert_goals.php)
        const response = await fetch('insert_goals.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            goal_id: goalId,
            target_value: targetValue  // Send target_value instead of target_weight
          })
        });

        const result = await response.json();
        if (result.success) {
          alert('Goal submitted successfully!');
          // Redirect to recommendations page or another page
          const encodedGoals = encodeURIComponent(selectedGoal);
          window.location.href = `recommendations.html?selectedGoals=${encodedGoals}`;
        } else {
          alert(`Goal Already Selected`);
        }
      } else {
        alert('Please select a valid goal.');
      }
    }

    // Render the goal buttons on page load
    renderGoalButtons();
  </script>
  
</body>
</html>

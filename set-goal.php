<?php

require_once 'database.php';
// Check if habit is selected
if (isset($_GET['habit_id'])) {
    $habit_id = $_GET['habit_id'];
    
    // Fetch habit details
    $sql = "SELECT * FROM habit_types WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $habit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $habit = $result->fetch_assoc();
}
?>
<!-- Form to set/update goal -->
<form action="set-goal.php" method="POST">
    <label for="goal">Set Goal:</label>
    <input type="number" name="goal" required>
    <label for="time_frame">Time Frame:</label>
    <select name="time_frame">
        <option value="daily">Daily</option>
        <option value="weekly">Weekly</option>
        <option value="monthly">Monthly</option>
    </select>
    <input type="hidden" name="habit_id" value="<?php echo $habit['id']; ?>">
    <button type="submit">Save Goal</button>
</form>

<?php
// Handling the goal saving
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $goal = $_POST['goal'];
    $time_frame = $_POST['time_frame'];
    $habit_id = $_POST['habit_id'];

    // Insert or update goal in the user_habits table
    $sql = "INSERT INTO user_habits (user_id, habit_type_id, goal, time_frame) 
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE goal = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiss", $_SESSION['user_id'], $habit_id, $goal, $time_frame, $goal);
    $stmt->execute();
    header("Location: dashboard.php"); // Redirect back to dashboard
}
?>

<?php
session_start();
require_once "database.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM user_habits WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

// Array to store all habits
$habits = [];

while ($row = $result->fetch_assoc()) {
    $habits[] = $row; // Add each habit to the array
}

// Process each habit
foreach ($habits as $habit) {
    $habit_type_id = $habit['habit_type_id'];
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Sanitize and fetch form values
    $user_id = $_SESSION['user_id'];
    $habit_id = $habit['id']; // Hidden input field from the form
    $goal = intval($_POST['goal']); // New goal frequency
    $time_frame = $_POST['time-interval']; // New time interval

    if ($goal <= 0) {
        echo "Invalid goal value.";
        exit();
    }

    // Update the goal in the `user_habits` table
    $sql = "UPDATE user_habits SET goal = ?, time_frame = ?, last_updated = NOW() WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isii", $goal, $time_frame, $habit_id, $user_id);

    if ($stmt->execute()) {
        // Redirect back to the member dashboard after success
        header("Location: member-dashboard.php");
        exit();
    } else {
        echo "Error updating goal: " . $conn->error;
    }

} else {
    echo "No data submitted.";
}
?>

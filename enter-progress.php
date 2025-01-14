<?php
session_start();
require_once "database.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// Query to get all habits for the user
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


// Handling the progress saving
if (isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id'];
    $progress = $_POST['progress'];
    $habit_id = $habit['id'];

    if ($progress <= 0) {
        header("Location: member-dashboard.php?error=Invalid progress value.");
        exit();
    }

    // Update progress in the user_habits table
    $sql = "UPDATE user_habits SET progress = progress + ?, last_updated = NOW() WHERE user_id = ? AND habit_type_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $progress, $_SESSION['user_id'], $habit_id);
    if ($stmt->execute()) {
    
    $sql_insert = "INSERT INTO user_habit_progress (user_id, habit_type_id, progress, timestamp) 
    VALUES (?, ?, ?, NOW())"; // Initial progress is set to 0
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iis", $user_id, $habit_type_id, $progress);

    if ($stmt_insert->execute()) {
        // Redirect to the member dashboard after successful habit creation
        header("Location: member-dashboard.php");
        exit();
    } else {
    // Handle error during insertion
        echo "Error: Could not create habit.";
    }

    } else {
        echo "Error updating progress: " . $conn->error;
    }
} else {
    echo "No data submitted.";
}
?>
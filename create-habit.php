<?php
session_start();
require_once "database.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Sanitize and fetch form values
    $user_id = $_SESSION['user_id'];
    $habit_type = $_POST['habit-type'];
    $goal = $_POST['goal'];
    $time_frame = $_POST['time-interval'];
    
    // Fetch the habit_type_id from the habit_types table
    $sql = "SELECT id FROM habit_types WHERE habit_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $habit_type);
    $stmt->execute();
    $result = $stmt->get_result();
    $habit_type_id = null;
    
    if ($result->num_rows > 0) {
        $habit_data = $result->fetch_assoc();
        $habit_type_id = $habit_data['id'];
    } else {
        // Handle error if habit type doesn't exist
        echo "Error: Habit type not found.";
        exit();
    }

    // Insert new habit into user_habits table
    $sql_insert = "INSERT INTO user_habits (user_id, habit_type_id, time_frame, goal, progress, last_updated) 
                   VALUES (?, ?, ?, ?, 0, NOW())"; // Initial progress is set to 0
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iiss", $user_id, $habit_type_id, $time_frame, $goal);
    
    if ($stmt_insert->execute()) {
        // Redirect to the member dashboard after successful habit creation
        header("Location: member-dashboard.php");
        exit();
    } else {
        // Handle error during insertion
        echo "Error: Could not create habit.";
    }
}
?>

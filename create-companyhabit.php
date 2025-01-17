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

    if ($goal <= 0) {
        header("Location: admin-dashboard.php?error=Invalid goal value.");
        exit();
    }
    
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

    $sql = "SELECT habit_type_id FROM company_habits";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['habit_type_id'] == $habit_type_id) {
                // Redirect back to the admin dashboard if habit already exists
                header("Location: admin-dashboard.php?error=Habit already exists.");
                exit();
            }
        }
    }

    // Insert new habit into company_habits table
    $sql_insert = "INSERT INTO company_habits (habit_type_id, time_frame, goal, progress, last_updated) 
                   VALUES (?, ?, ?, 0, NOW())"; // Initial progress is set to 0
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iss", $habit_type_id, $time_frame, $goal);
    
    if ($stmt_insert->execute()) {
        // Redirect to the admin dashboard after successful habit creation
        header("Location: admin-dashboard.php");
        exit();
    } else {
        // Handle error during insertion
        echo "Error: Could not create company habit.";
    }
}
?>

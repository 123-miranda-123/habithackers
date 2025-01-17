<?php
session_start();
require_once "database.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if (!isset($_GET["type_id"])) {
    header("Location: admin-dashboard.php");
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Sanitize and fetch form values
    $goal = $_POST['goal']; // New goal frequency
    $time_frame = $_POST['time-interval']; // New time interval

    if (isset($_GET['type_id']) && is_numeric($_GET['type_id'])) {
        $habit_type_id = $_GET['type_id'];
    } else {
        // Handle error or invalid input
        exit("Invalid habit type.");
    }

    if ($goal <= 0) {
        echo "Invalid goal value.";
        exit();
    }

    // Update the goal in the `company_habits` table
    $sql = "UPDATE company_habits SET goal = ?, time_frame = ?, last_updated = NOW() WHERE habit_type_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $goal, $time_frame, $habit_type_id);

    if ($stmt->execute()) {
        // Redirect back to the admin dashboard after success
        header("Location: admin-dashboard.php");
        exit();
    } else {
        echo "Error updating goal: " . $conn->error;
    }

} else {
    echo "No data submitted.";
}
?>

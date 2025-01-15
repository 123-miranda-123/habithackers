<?php
session_start();
require_once "database.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if (!isset($_SESSION["typ_id"])) {
    header("Location: member-dashboard.php");
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Sanitize and fetch form values
    $user_id = $_SESSION['user_id'];
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

    $sql = "UPDATE user_habits SET progress = progress + ?, last_updated = NOW() WHERE user_id = ? AND habit_type_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $progress, $_SESSION['user_id'], $habit_type_id);

    // Update the goal in the `user_habits` table
    $sql = "UPDATE user_habits SET goal = ?, time_frame = ?, last_updated = NOW() WHERE user_id = ? AND habit_type_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isii", $goal, $time_frame, $_SESSION['user_id'], $habit_type_id);
);

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

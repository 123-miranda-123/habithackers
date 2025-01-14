<?php
session_start();
require_once "database.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handling the progress saving
if (isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id'];
    $progress = $_POST['progress'];
    $habit_id = $_POST['habit_id'];

    if ($progress <= 0) {
        echo "Invalid goal value.";
        exit();
    }

    // Update progress in the user_habits table
    $sql = "UPDATE user_habits SET progress = progress + ?, last_updated = NOW() WHERE user_id = ? AND habit_type_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $progress, $_SESSION['user_id'], $habit_id);
    if ($stmt->execute()) {
        header("Location: member-dashboard.php"); // Redirect back to dashboard
    } else {
        echo "Error updating progress: " . $conn->error;
    }

} else {
    echo "No data submitted.";
}
?>
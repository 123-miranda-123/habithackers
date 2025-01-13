<?php
session_start();
require_once "database.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Check if the habit_type_id parameter is provided
if (isset($_GET['habit_type_id'])) {
    $habit_type_id = $_GET['habit_type_id'];

    // Delete the habit progress associated with the user (in the 'user_habits' table)
    $sql_delete_progress = "DELETE FROM user_habits WHERE habit_type_id = ? AND user_id = ?";
    $stmt_delete_progress = $conn->prepare($sql_delete_progress);
    $stmt_delete_progress->bind_param("ii", $habit_type_id, $_SESSION['user_id']);
    $stmt_delete_progress->execute();

    // Optionally delete any progress related to the team or company in their respective tables (if required)
    // But you mentioned to keep the habit type, so we're keeping that data intact.

    // Redirect back to the dashboard after the deletion
    header("Location: member-dashboard.php");
    exit();
} else {
    echo "No habit selected for deletion.";
    exit();
}
?>

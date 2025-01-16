<?php
session_start();
require_once "database.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_role = $_SESSION["user_role"];
$user_id = $_SESSION["user_id"];

// Check if the habit_type_id parameter is provided
if (isset($_GET['habit_type_id'])) {
    $habit_type_id = $_GET['habit_type_id'];

    if ($user_role = "Member") {
    // Delete the habit progress associated with the user (in the 'user_habits' table)
        $sql_delete_progress = "DELETE FROM user_habits WHERE habit_type_id = ? AND user_id = ?";
        $stmt_delete_progress = $conn->prepare($sql_delete_progress);
        $stmt_delete_progress->bind_param("ii", $habit_type_id, $user_id);
        $stmt_delete_progress->execute();
    } 

    else if ($user_role = "Captain") {

        $sql = "SELECT id FROM teams WHERE captain_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $team_row = $result->fetch_assoc();
        $team_id = $team_row['id'];

        $sql_delete_progress = "DELETE FROM team_habits WHERE habit_type_id = ? AND team_id = ?";
        $stmt_delete_progress = $conn->prepare($sql_delete_progress);
        $stmt_delete_progress->bind_param("ii", $habit_type_id, $team_id);
        $stmt_delete_progress->execute();
        } 

    else if ($user_role = 'Admin') {
        $sql_delete_progress = "DELETE FROM company_habits WHERE habit_type_id = ?";
        $stmt_delete_progress = $conn->prepare($sql_delete_progress);
        $stmt_delete_progress->bind_param("i", $habit_type_id);
        $stmt_delete_progress->execute();
        }

    // Redirect back to the dashboard after the deletion
    header("Location: login.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>

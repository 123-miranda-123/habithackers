<?php
session_start();
require_once "database.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM team_members WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User is part of a team, fetch team details
    $team_row = $result->fetch_assoc();
    $team_id = $team_row['team_id'];
} 

// Handling the progress saving
if (isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id'];
    $progress = $_POST['progress'];

    if (isset($_GET['type_id']) && is_numeric($_GET['type_id'])) {
        $habit_type_id = $_GET['type_id'];
    } else {
        // Handle error or invalid input
        header("Location:member-dashboard.php?error=Invalid Input");
        exit();
    }
    
    if ($progress <= 0) {
        header("Location: member-dashboard.php?error=Invalid progress value.");
        exit();
    }

    // Update progress in the user_habits table
    $sql = "UPDATE user_habits SET progress = progress + ?, last_updated = NOW() WHERE user_id = ? AND habit_type_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $progress, $_SESSION['user_id'], $habit_type_id);
    if ($stmt->execute()) {

        // Insert into user_habit_progress
        $sql_insert = "INSERT INTO user_habit_progress (user_id, habit_type_id, progress, timestamp) 
                       VALUES (?, ?, ?, NOW())";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iii", $user_id, $habit_type_id, $progress);
        if (!$stmt_insert->execute()) {
            echo "Error inserting user habit progress.";
            exit();
        }

        // Insert into team_habit_progress if the user belongs to a team
        if ($team_id) {
            $sql_insert_team = "INSERT INTO team_habit_progress (team_id, habit_type_id, progress, timestamp) 
                                VALUES (?, ?, ?, NOW())";
            $stmt_insert_team = $conn->prepare($sql_insert_team);
            $stmt_insert_team->bind_param("iii", $team_id, $habit_type_id, $progress);
            if (!$stmt_insert_team->execute()) {
                header("Location: member-dashboard.php?error=" . urlencode("Error inserting team habit progress."));
                exit();
            }

            $sql_update = "UPDATE team_habits SET progress = progress + ?, last_updated = NOW() WHERE team_id = ? AND habit_type_id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("iii", $progress, $team_id, $habit_type_id);

            if (!$stmt_update->execute()) {
                header("Location: member-dashboard.php?error=" . urlencode("Error updating team habit progress."));
                exit();
            }

        }

        // Insert into company_habit_progress
        $sql_insert_company = "INSERT INTO company_habit_progress (habit_type_id, progress, timestamp) 
                               VALUES (?, ?, NOW())";
        $stmt_insert_company = $conn->prepare($sql_insert_company);
        $stmt_insert_company->bind_param("ii", $habit_type_id, $progress);
        if (!$stmt_insert_company->execute()) {
            echo "Error inserting company habit progress.";
            exit();
        }

        $sql_updatecompany = "UPDATE company_habits SET progress = progress + ?, last_updated = NOW() WHERE habit_type_id = ?";
        $stmt_updatecompany = $conn->prepare($sql_updatecompany);
        $stmt_updatecompany->bind_param("ii", $progress, $habit_type_id);
        if (!$stmt_updatecompany->execute()) {
            header("Location: member-dashboard.php?error=" . urlencode("Error updating company habit progress."));
            exit();
        }
        // Redirect to the member dashboard after successful progress update
        header("Location: member-dashboard.php");
        exit();
    } else {
        echo "Error updating progress: " . $conn->error;
    }
}

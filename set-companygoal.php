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


$sql = "SELECT id FROM teams WHERE captain_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User is captain of a team, fetch team details
    $team_row = $result->fetch_assoc();
    $team_id = $team_row['id'];

    // Fetch the team name
    $sql_team = "SELECT name FROM teams WHERE id = ?";
    $stmt_team = $conn->prepare($sql_team);
    $stmt_team->bind_param("i", $team_id);
    $stmt_team->execute();
    $result_team = $stmt_team->get_result();
    $team_name = '';

    if ($result_team->num_rows > 0) {
        $team_data = $result_team->fetch_assoc();
        $team_name = $team_data['name'];
    }
} else {
    // User is not part of any team
    $team_name = "No team created yet.";
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

    // Update the goal in the `user_habits` table
    $sql = "UPDATE team_habits SET goal = ?, time_frame = ?, last_updated = NOW() WHERE user_id = ? AND habit_type_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isii", $goal, $time_frame, $team_id, $habit_type_id);

    if ($stmt->execute()) {
        // Redirect back to the member dashboard after success
        header("Location: captain-dashboard.php");
        exit();
    } else {
        echo "Error updating goal: " . $conn->error;
    }

} else {
    echo "No data submitted.";
}
?>

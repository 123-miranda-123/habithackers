<?php
session_start();
require_once "database.php";

// Assume the captain has successfully logged in and their user ID is saved in session
$user_id = $_SESSION['user_id'];

// Check if the user is a captain and if they already have a team
$sql = "SELECT * FROM teams WHERE captain_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Redirect based on whether the captain has a team
if ($result->num_rows > 0) {
    // The captain is already in a team, redirect to the dashboard
    header("Location: captain-dashboard.php"); // Change this to the correct dashboard URL
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Team - Habit Hub</title>
    <link href="styles/header.css" rel="stylesheet" type="text/css"/>
    <link href="styles/login.css" rel="stylesheet" type="text/css"/>
    <link rel="icon" href="images/icon.png" type="image/png">
</head>
<body>
<section class = "container">
    <form action="create-team.php" method="POST">
        <h2>Create a New Team</h2>

        <div id = "message">
        <?php
        if (isset($_GET['message'])) {
            echo '<p>' . htmlspecialchars($_GET['message']) . '</p>';
        }
        ?>
        </div>

        <div class = "input-group">
            <label for="team_name" >Team Name:</label>
            <input type="text" id="team_name" name="team_name" required>
        </div>

        <div class = "submit-btn">
            <button type="submit" name = "submit">Create Team</button>
        </div>
    </form>
</section>
    
</body>
</html>

<?php

if (isset($_POST["submit"])) {
    $captain_id = $_SESSION['user_id']; // The ID of the logged-in captain
    $team_name = $_POST['team_name'];

        // Insert the new team into the database
        $sql = "INSERT INTO teams (name, captain_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $team_name, $captain_id);

        if ($stmt->execute()) {
            header("Location: captain-dashboard.php?message=" . urlencode("Team created successfully!"));
            exit(); // Make sure no further code is executed after the redirect
        } else {
            header("Location: create-team.php?message=" . urlencode("Error creating team: " . $stmt->error));
            exit(); // Make sure no further code is executed after the redirect
        }
    
}
?>

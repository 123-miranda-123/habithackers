<?php
session_start();
require_once "database.php";

// Assume the captain has successfully logged in and their user ID is saved in session
$user_id = $_SESSION['user_id'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rename Team - Habit Hub</title>
    <link href="styles/header.css" rel="stylesheet" type="text/css"/>
    <link href="styles/login.css" rel="stylesheet" type="text/css"/>
    <link rel="icon" href="images/icon.png" type="image/png">
</head>
<body>
<section class = "container">
    <form action="rename-team.php" method="POST">
        <h2>Rename Team</h2>

        <div id = "message">
        <?php
        if (isset($_GET['message'])) {
            echo '<p>' . htmlspecialchars($_GET['message']) . '</p>';
        }
        ?>
        </div>

        <div class = "input-group">
            <label for="team_name" >New Team Name:</label>
            <input type="text" id="team_name" name="team_name" required>
        </div>

        <div class = "submit-btn">
            <button type="submit" id = "submit" name = "submit">Rename Team</button>
            <button type="cancel" id = "cancel" name = "cancel"><a href = "captain-dashboard.php">Cancel</a></button>
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
        $sql = "UPDATE teams SET name = ? where captain_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $team_name, $captain_id);

        if ($stmt->execute()) {
            header("Location: captain-dashboard.php?message=" . urlencode("Team renamed successfully!"));
            exit(); // Make sure no further code is executed after the redirect
        } else {
            header("Location: captain-dashboard.php?message=" . urlencode("Error renaming team: " . $stmt->error));
            exit(); // Make sure no further code is executed after the redirect
        }
    
}
?>

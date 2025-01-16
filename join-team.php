<?php
require_once "database.php";
session_start();
// Assume the user has successfully logged in and their user ID is saved in session
$user_id = $_SESSION['user_id'];

// Check if the user is already in a team
$sql = "SELECT * FROM team_members WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Redirect based on whether the user is in a team
if ($result->num_rows > 0) {
    // The user is already in a team, redirect to the dashboard
    header("Location: member-dashboard.php"); // Change this to the correct dashboard URL
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Team - Habit Hub</title>
    <link href="styles/header.css" rel="stylesheet" type="text/css"/>
    <link href="styles/login.css" rel="stylesheet" type="text/css"/>
    <link rel="icon" href="images/icon.png" type="image/png">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body>
<section class="container">
    <form action="join-team.php" method="POST">
        <h2>Join A Team</h2>

        <div id="message">
        <?php
        if (isset($_GET['message'])) {
            echo '<p>' . htmlspecialchars($_GET['message']) . '</p>';
        }
        ?>
        </div>

        <div class="input-group">
            <label for="team_id">Team ID Number:</label>
            <input type="text" id="team_id" name="team_id" required>
        </div>

        <div class="submit-btn">
            <button id = "submit" type="submit" name="submit">Join Team</button>
        </div>
    </form>
</section>
</body>
</html>

<?php
// Check if the form is submitted
if (isset($_POST["submit"])) {
    $team_id = $_POST['team_id'];  // Corrected to $_POST
    $user_id = $_SESSION['user_id'];  // The ID of the logged-in member
    $role = 'member'; // Assign the role as 'member' since they're joining a team


    $sql = "SELECT * FROM teams WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $team_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Checks if team exists
    if ($result->num_rows > 0) {

        // Insert the member into the team
        $sql = "INSERT INTO team_members (user_id, team_id, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $user_id, $team_id, $role);

        if ($stmt->execute()) {
            header("Location: member-dashboard.php?message=" . urlencode("Successfully joined team!"));
            exit();  
        } else {
            header("Location: join-team.php?message=" . urlencode("Error joining team: " . $stmt->error));
            exit();
        }
    } else {
        header("Location: join-team.php?message=" . urlencode("Team does not exist."));
        exit();
    }
}

?>

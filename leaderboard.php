<?php
require_once "database.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$min_fac = 1;
$pag_fac = 2;
$ent_fac = 5;
$cup_fac = 15;
$hrs_fac = 60;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link href="styles/header.css" rel="stylesheet" type="text/css"/>
    <link href="styles/member-dashboard.css" rel="stylesheet" type="text/css"/>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body>

    <nav class="header">
        <div class="header-container">
            <a href="index.php">
                <img src="images/Banner 2.png" alt="Habit Hub Logo" class="logo">
            </a>
            <div class="auth-buttons">
                <a href="leaderboard.php" id="leaderboard">Leaderboard</a>
                <a href="help.html" id="help">Help</a>
                <a href="user-profile.php" id="user-profile" class="user-profile-link">User Profile</a>
                <a href="logout.php" id="logout">Logout</a>
            </div>
        </div>
    </nav>

<section class = "leaderboard" class = "container">

<img src="images/trophyicon.png" width = 80px alt="Gamification Icon" class="feature-icon">
<h1>Company Rankings</h1>
<?php

$users = array();

$sql = "SELECT id FROM users WHERE role = 'Member'";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}

// Initialize the $users array with user IDs and scores set to 0
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[$row["id"]] = 0;
}

// Step 2: Ensure $rates array is defined
// Example $rates mapping habit_type_id to values
$rates = [
    1 => 1,  // Example: habit_type_id 1 has a rate of 10
    2 => 2,  // Example: habit_type_id 2 has a rate of 20
    3 => 5,
    4 => 1,
    5=> 15,
    6=> 60,
    7=> 60,
    8=> 60
];

// Step 3: Fetch user habit progress and calculate scores
$sql = "SELECT user_id, habit_type_id, progress FROM user_habit_progress";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    $habitTypeId = $row["habit_type_id"];
    $progress = $row["progress"];
    $userId = $row["user_id"];
    
    if (isset($rates[$habitTypeId])) {
        // Update the user's score
        $users[$userId] += $rates[$habitTypeId] * $progress;
    }
}

// Step 4: Sort users by their scores in ascending order
arsort($users);

// Optionally, print the sorted $users array

    // Display Individual Leaderboard
echo "<h2>Individual Leaderboard</h2>";
echo "<table>";
echo "<tr><th>Rank</th><th>User Name</th><th>Total Points</th></tr>";

$rank = 1;
foreach ($users as $user => $value) {
    $sql = "SELECT * FROM users where id = '$user'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo "<tr><td>" . $rank++ . "</td><td>" . htmlspecialchars($row['name']) . "</td>
    <td>" . $value ."</td>";
}
echo "</table>";
?>
<div>
<?php
// Display Team Leaderboard
$teams = array();

$sql = "SELECT id FROM teams";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}

$teams = [];
// Initialize the $users array with user IDs and scores set to 0
while ($row = $result->fetch_assoc()) {
    $teams[$row["id"]] = 0;
}

// Step 3: Fetch user habit progress and calculate scores
$sql = "SELECT team_id, habit_type_id, progress FROM team_habit_progress";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    $habitTypeId = $row["habit_type_id"];
    $progress = $row["progress"];
    $teamId = $row["team_id"];
    
    if (isset($rates[$habitTypeId])) {
        // Update the user's score
        $teams[$teamId] += $rates[$habitTypeId] * $progress;
    }
}

// Step 4: Sort users by their scores in ascending order
arsort($teams);
echo "<h2>Team Leaderboard</h2>";
echo "<table>";
echo "<tr><th>Rank</th><th>Team</th><th>Total Points</th></tr>";

$rank = 1;
foreach ($teams as $team => $value) {
    $sql = "SELECT * FROM teams where id = '$team'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo "<tr><td>" . $rank++ . "</td><td>" . htmlspecialchars($row['name']) . "</td>
    <td>" . $value ."</td>";
}
echo "</table>";
    ?>
</div>
</section>
</body>
</html>
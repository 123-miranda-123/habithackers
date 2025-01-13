<?php
require_once "database.php";
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}
if ($conn->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    // Display Individual Leaderboard
echo "<h2>Individual Leaderboard</h2>";
echo "<table>";
echo "<tr><th>Rank</th><th>User Name</th><th>Progress (%)</th><th>Total Progress</th></tr>";

$rank = 1;
while ($row = $result_individual->fetch_assoc()) {
    echo "<tr><td>" . $rank++ . "</td><td>" . htmlspecialchars($row['user_name']) . "</td><td>" . 
         number_format($row['progress_percentage'], 2) . "%</td><td>" . 
         number_format($row['total_normalized_progress'], 2) . "</td></tr>";
}
echo "</table>";

// Display Team Leaderboard
echo "<h2>Team Leaderboard</h2>";
echo "<table>";
echo "<tr><th>Rank</th><th>Team</th><th>Progress (%)</th><th>Total Progress</th></tr>";

$rank = 1;
while ($row = $result_team->fetch_assoc()) {
    // Fetch team name if you need to display it (assuming a `teams` table with `name`)
    $team_name_query = "SELECT name FROM teams WHERE id = " . $row['team_id'];
    $team_name_result = $conn->query($team_name_query);
    $team_name = $team_name_result->fetch_assoc()['name'];

    echo "<tr><td>" . $rank++ . "</td><td>" . htmlspecialchars($team_name) . "</td><td>" . 
         number_format($row['progress_percentage'], 2) . "%</td><td>" . 
         number_format($row['total_normalized_progress'], 2) . "</td></tr>";
}
echo "</table>";

    ?>
</body>
</html>
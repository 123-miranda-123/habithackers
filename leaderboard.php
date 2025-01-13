<?php
require_once "database.php";

if ($conn->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$sql_individual_leaderboard = "SELECT users.user_name, SUM(user_habits.progress * unit_conversion.factor) AS total_normalized_progress, (SUM(user_habits.progress * unit_conversion.factor) / SUM(habit_types.goal)) * 100 AS individual_progress_percentage
FROM user_habits
JOIN users ON user_habits.user_id = users.user_id
JOIN habit_types ON user_habits.habit_type_id = habit_types.id
JOIN unit_conversion ON habit_types.unit = unit_conversion.unit
GROUP BY users.user_id
ORDER BY individual_progress_percentage DESC, total_normalized_progress DESC;";
$result_individual = $conn->query($sql_individual_leaderboard);

$sql_team_leaderboard = " SELECT teams.name AS team_name, SUM(user_habits.progress * unit_conversion.factor) AS total_normalized_progress, (SUM(user_habits.progress * unit_conversion.factor) / SUM(habit_types.goal)) * 100 AS team_progress_percentage
FROM user_habits
JOIN users ON user_habits.user_id = users.user_id
JOIN habit_types ON user_habits.habit_type_id = habit_types.id
JOIN unit_conversion ON habit_types.unit = unit_conversion.unit
JOIN team_members ON users.user_id = team_members.user_id
JOIN teams ON team_members.team_id = teams.id
GROUP BY teams.id
ORDER BY team_progress_percentage DESC, total_normalized_progress DESC;

";
$result_team = $conn->query($sql_team_leaderboard);
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
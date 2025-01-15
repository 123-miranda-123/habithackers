<?php
session_start();
require_once "database.php";
require_once "reset-progress.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Check if user already exists in the 'team_members' table (user is already part of a team)
$sql = "SELECT * FROM team_members WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User is part of a team, fetch team details
    $team_row = $result->fetch_assoc();
    $team_id = $team_row['team_id'];

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
    $team_name = "No team assigned yet.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard - Habit Hub</title>
    
    <link href="styles/header.css" rel="stylesheet" type="text/css"/>
    <link href="styles/member-dashboard.css" rel="stylesheet" type="text/css"/>
    <link rel="icon" href="images/icon.png" type="image/png">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<nav class="header">
    <div class="header-container">
        <a href="index.php">
            <img src="images/Banner 2.png" alt="Habit Hub Logo" class="logo">
        </a>
        <div class="auth-buttons">
            <a href ="leaderboard.php" id="leaderboard">Leaderboard</a>
            <a href="help.html" id="help">Help</a>
            <a href="user-profile.php" id="user-profile">User Profile</a>
            <a href="logout.php" id="logout">Logout</a>
        </div>
    </div>
</nav>

<section class="container">
    <div id = "title">
        <h2>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>
        <h1>Member Dashboard</h1>
    </div>
    <p>Team Name: <?php echo htmlspecialchars($team_name); ?></p>
    <button class="open-btn" onclick="openPopup()">+ Create a New Habit</button>

    <?php
    // Fetch user's habits from the user_habits table
    $sql = "SELECT user_habits.*, habit_types.habit_name, habit_types.unit 
            FROM user_habits
            JOIN habit_types ON user_habits.habit_type_id = habit_types.id
            WHERE user_habits.user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h2>Your Habit Logs</h2>";

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Habit Type</th>
        <th>Your Progress</th>
        <th>Time Frame</th>
        <th>Team Goal</th>
        <th>Company Goal</th>
        <th>Actions</th></tr>";

        while ($row = $result->fetch_assoc()) {
            // Fetch team goal and company goal
            $habit_name = $row['habit_name'];
            $unit = $row['unit'];

            // Fetch team goal
            $team_goal_sql = "SELECT goal FROM team_habits JOIN habit_types ON team_habits.habit_type_id = habit_types.id
            WHERE habit_name = ? AND team_id = ?";
            $team_stmt = $conn->prepare($team_goal_sql);
            $team_stmt->bind_param("si", $habit_name, $_SESSION['team_id']);
            $team_stmt->execute();
            $team_goal_result = $team_stmt->get_result();
            if ($team_goal_result->num_rows > 0) {
                $team_goal = $team_goal_result->fetch_assoc()['goal'];
            } else {
                $team_goal = null;
            }

            // Fetch company goal
            $company_goal_sql = "SELECT goal FROM company_habits JOIN habit_types ON company_habits.habit_type_id = habit_types.id WHERE habit_name = ?";
            $company_stmt = $conn->prepare($company_goal_sql);
            $company_stmt->bind_param("s", $habit_name);
            $company_stmt->execute();
            $company_goal_result = $company_stmt->get_result();
            if ($company_goal_result->num_rows > 0) {
                $company_goal = $company_goal_result->fetch_assoc()['goal'];
            } else {
                $company_goal = null;
            }

            $progress_percentage = ($row['progress'] / $row['goal']) * 100;
            $progress_percentage = min(100, $progress_percentage); // Ensure max of 100%

            echo "<tr>";
            echo "<td>" . $row['habit_name'] . "</td>";
            echo "<td>
                    <div class='progress-bar'>
                        <div class='progress' style='width: " . $progress_percentage . "%;'></div>
                    </div>
                    " . $row['progress'] . " " . $row['unit'] . " / " . $row['goal'] . " " . $row['unit'] . "
                  </td>";
            echo "<td>" . $row['time_frame'] . "</td>";
            echo "<td>" . ($team_goal ? $team_goal . " " . $unit : "Not set") . "</td>";
            echo "<td>" . ($company_goal ? $company_goal . " " . $unit : "Not set") . "</td>";
            echo "<td>
                    <button id = 'set-goal' class='open-btn' onclick='openPopup2(".$row['habit_type_id'].")'>Update Goal</button>
                    <button id = 'enter-progress' class='open-btn' onclick='openPopup3(".$row['habit_type_id'].")'>Enter Progress</button>
                    <button id = 'delete-goal'><a href='delete-goal.php?habit_type_id=" . $row['habit_type_id'] . "'>Delete</a></button>
                  </td>";
            echo "<input type='hidden' name='habit_type_id' value='" . $row['habit_type_id'] . "'>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No habits found for this user. Please add some habits.</p>";
    }
    ?>

    <div id="overlay" class="overlay">
    <div class="popup">
    <form action="create-habit.php" method="POST">
          <h2>Create a New Habit</h2>

            <div class="input-group">
                <label for="number">Frequency (Goal)</label>
                <input type="number" id="goal" name="goal" required>
            </div>

            <div class="input-group">
                <select name="habit-type" id="habit-type" class = "dropdown" required>
                <option value="" disabled selected>Select Habit Type</option>
                <?php while ($row_habit_type = $result_habit_type->fetch_assoc()) { ?>
            <option value="<?php echo $row_habit_type['habit_name']; ?>" data-unit="<?php echo $row_habit_type['unit']; ?>">
                <?php echo $row_habit_type['habit_name'] . " (" . $row_habit_type['unit'] . ")"; ?>
            </option>
            <?php } ?>
                </select>
            </div>

            <div class="input-group">
                <select name="time-interval" id="time-interval" class = "dropdown" required>
                    <option value="" disabled selected>Select Time Interval</option>
                    <option value="Daily">Daily</option>
                    <option value="Weekly">Weekly</option>
                    <option value="Monthly">Monthly</option>
                </select>
            </div>

            <div>
              <button class="cancel-btn" onclick

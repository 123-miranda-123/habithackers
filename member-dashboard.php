<?php
session_start();
require_once "database.php";

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
</head>
<body>
<nav class="header">
        <div class="header-container">
            <a href="index.php">
                <img src="images/Banner 2.png" alt="Habit Hub Logo" class="logo">
            </a>
            <div class="auth-buttons">
                <a href="help.html" id="help">Help</a>
                <a href="logout.php" id="logout">Logout</a>
            </div>
        </div>
</nav>
<section class="container">
    <div id = "title">
        <h1>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>
        <h1>Team <?php echo htmlspecialchars($team_name); ?></p>
    </div>

    <h3>Your Habit Logs</h3>
    <?php if (empty($habit_logs)): ?>
        <p>No habit logs available yet. Start logging your habits!</p>
    <?php else: ?>
        <ul>
            <?php foreach ($habit_logs as $habit): ?>
                <li><?php echo htmlspecialchars($habit); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    
    <button class="open-btn" onclick="openPopup()">Open Popup</button>

    <div id="overlay" class="overlay">
    <div class="popup">
    <form action="create-habit.php" method="POST">
          <h2>Create a New Habit</h2>
          <div class="input-group">
                <select name="habit-type" id="habit-type" class = "dropdown" required>
                    <option value="" disabled selected>Select Habit Type</option>
                    <option value="exercise">Exercise</option>
                    <option value="reading">Reading</option>
                    <option value="journaling">Journaling</option>
                    <option value="meditation">Meditation</option>
                    <option value="hydration">Hydration</option>
                    <option value="sleep">Sleep</option>
                    <option value="project">Project</option>
                    <option value="skill">Skill Learning</option>
                </select>
            </div>
            <div class="input-group">
                <label for="date">Start Date</label>
                <input type="text" id="date" name="date" placeholder="dd/mm/yyyy" required>
            </div>

            <div class="input-group">
                <label for="number">Frequency</label>
                <input type="number" id="number" name="number" required>
            </div>

            <div class="input-group">
                <select name="habit-type" id="habit-type" class = "dropdown" required>
                    <option value="" disabled selected>Select Time Interval</option>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>

            <div>
              <button class="cancel-btn" onclick="closePopup()">Cancel</button>
              <button class="submit-btn">Submit</button>
            </div>


  <script>
    function openPopup() {
      document.getElementById('overlay').style.display = 'flex';
      document.getElementById('main-content').classList.add('greyed-out');
    }

    function closePopup() {
      document.getElementById('overlay').style.display = 'none';
      document.getElementById('main-content').classList.remove('greyed-out');
    }
  </script>
</body>
</html>

</section>
</body>
</html>

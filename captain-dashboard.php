<?php
session_start();
require_once("database.php");

if (($_SESSION["user_role"]) !== "Captain") {
    header("Location: index.php");
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}
if ($conn->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Captain Dashboard - Habit Hub</title>
    <link href="styles/header.css" rel="stylesheet" type="text/css"/>
    <link href="styles/manage-dashboard.css" rel="stylesheet" type="text/css"/>
    <link rel="icon" href="images/icon.png" type="image/png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body>
    <script>
        function openEdit(id) {
            window.location.href = 'edit-user.php?id=' + encodeURIComponent(id);
        }
    </script>
    <nav class="header">
        <div class="header-container">
            <a href="index.php">
                <img src="images/Banner 2.png" alt="Habit Hub Logo" class="logo">
            </a>
            <div class="auth-buttons">
                <a href="leaderboard.php" id="leaderboard">Leaderboard</a>
                <a href="help.html" id="help">Help</a>
                <a href="logout.php" id="logout">Logout</a>
                <!-- Added User Profile Link -->
                <a href="user-profile.php" id="user-profile" class="user-profile-link">User Profile</a>
            </div>
        </div>
    </nav>

    <section class="container">
        <h2>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>
        <h1>Captain Dashboard</h1>

        <div id="title">
            <p>Team Name: <?php echo htmlspecialchars($team_name); ?></p>
            <p>Team ID: <?php echo htmlspecialchars($team_id); ?></p>
        </div>

        <h2>Team Members</h2>
        <table class="users-table">
            <tr>
                <th>User ID</th>
                <th>Member Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        
        <?php
            $sql = "SELECT * FROM team_members";
            $result = $conn->query($sql);
            if (!$result) {
                echo "Invalid query: " . $connection->error;
                exit();
            }
            while ($row = $result->fetch_assoc()) {
                echo "
                <tr> 
                <td id='user-id'>" . $row["id"] . "</td>
                <td>" . $row["name"] . "</td>
                <td>" . $row["email"] . "</td>
                <td>
                    <button id='edit' onclick='openEdit(".$row["id"].")'>Edit</button>
                    <button id='delete' onclick='openDelete(".$row["id"].")'>Remove</button>
                </td>
            </tr>"; 
            }
        ?>
        </table>

        <button class="open-btn" onclick="openPopup()">+ Create a New Team Habit</button>

        <?php
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
                $habit_name = $row['habit_name'];
                $unit = $row['unit'];

                // Fetch team goal
                $team_goal_sql = "SELECT goal FROM team_habits JOIN habit_types ON team_habits.habit_type_id = habit_types.id
                WHERE habit_name = ? AND team_id = ?";
                $team_stmt = $conn->prepare($team_goal_sql);
                $team_stmt->bind_param("si", $habit_name, $_SESSION['team_id']);
                $team_stmt->execute();
                $team_goal_result = $team_stmt->get_result();
                $team_goal = ($team_goal_result->num_rows > 0) ? $team_goal_result->fetch_assoc()['goal'] : null;

                // Fetch company goal
                $company_goal_sql = "SELECT goal FROM company_habits JOIN habit_types ON company_habits.habit_type_id = habit_types.id WHERE habit_name = ?";
                $company_stmt = $conn->prepare($company_goal_sql);
                $company_stmt->bind_param("s", $habit_name);
                $company_stmt->execute();
                $company_goal_result = $company_stmt->get_result();
                $company_goal = ($company_goal_result->num_rows > 0) ? $company_goal_result->fetch_assoc()['goal'] : null;

                $progress_percentage = ($row['progress'] / $row['goal']) * 100;
                $progress_percentage = min(100, $progress_percentage); 

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
                        <button id='set-goal' class='open-btn' onclick='openPopup2(".$row['habit_type_id'].")'>Update Goal</button>
                        <button id='enter-progress' class='open-btn' onclick='openPopup3(".$row['habit_type_id'].")'>Enter Progress</button>
                        <button id='delete-goal'><a href='delete-goal.php?habit_type_id=" . $row['habit_type_id'] . "'>Delete</a></button>
                      </td>";
                echo "<input type='hidden' name='habit_type_id' value='" . $row['habit_type_id'] . "'>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No habits found for this user. Please add some habits.</p>";
        }
        ?>
    </section>
</body>
</html>

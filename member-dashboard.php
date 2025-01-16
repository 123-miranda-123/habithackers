<?php
session_start();
require_once "database.php";
require_once "reset-progress.php";

// Call the reset function when the page loads
reset_progress();

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
    header("Location: join-team.php");
    exit();
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
                <a href="logout.php" id="logout">Logout</a>
            </div>
        </div>
</nav>
<section class="container">
        <h2>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>
        <h1>Member Dashboard</h1>

    
            <p>Team Name: <?php echo htmlspecialchars($team_name); ?></p>
            <p>Team ID: <?php echo htmlspecialchars($team_id); ?></p>
            
    <button class="open-btn" onclick="openPopup()">+ Create a New Habit</button>

    <?php
// Fetch user's habits from the user_habits table
$sql = "SELECT user_habits.*, habit_types.habit_name, habit_types.unit 
        FROM user_habits
        JOIN habit_types ON user_habits.habit_type_id = habit_types.id
        WHERE user_habits.user_id = ?";

$stmt = $conn->prepare($sql);
// Bind the user_id parameter from the session to the query
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

    // Loop through the results and display the habit type with goal and progress
    while ($row = $result->fetch_assoc()) {
        // Fetch team goal and company goal
        $habit_name = $row['habit_name'];
        $unit = $row['unit'];

        // Fetch team goal
        $team_goal_sql = "SELECT goal FROM team_habits JOIN habit_types ON team_habits.habit_type_id = habit_types.id
        WHERE habit_name = ? AND team_id = ?";
        $team_stmt = $conn->prepare($team_goal_sql);
        $team_stmt->bind_param("si", $habit_name, $_SESSION['team_id']); // assuming team_id is stored in session
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
        $progress_percentage = min(100, $progress_percentage); // Make sure it doesn't exceed 100%


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
                <button id = 'delete'><a href='delete-goal.php?habit_type_id=" . $row['habit_type_id'] . "'>Delete</a></button>
              </td>";
        echo "<input type='hidden' name='habit_type_id' value='" . $row['habit_type_id'] . "'>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p>No habits found for this user. Please add some habits.</p>";
}
?>
<?php
$sql_habit_type = "SELECT * FROM habit_types"; 
$result_habit_type = $conn->query($sql_habit_type);
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
              <button class="cancel-btn" onclick="closePopup()">Cancel</button>
              <button class="submit-btn" id="submit" type="submit" name="submit">Submit</button>
            </div>

            
    </form>
    </div>
    </div>

    <div id="overlay2" class="overlay">
        <div class="popup">
            <form action=<?php echo "set-goal.php?type_id=".urlencode($_GET["type_id"]); ?> method="POST">
                <h2>Update Goal</h2>

                <div class="input-group">
                    <label for="goal">Frequency (Goal)</label>
                    <input type="number" id="goal" name="goal" required>
                </div>

                <div class="input-group">
                    <select name="time-interval" id="time-interval" class="dropdown" required>
                        <option value="" disabled selected>Select Time Interval</option>
                        <option value="Daily">Daily</option>
                        <option value="Weekly">Weekly</option>
                        <option value="Monthly">Monthly</option>
                    </select>
                </div>

                <div>
                    <button class="cancel-btn" onclick="closePopup2()">Cancel</button>
                    <button class="submit-btn" id="submit" type="submit" name="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <div id="overlay3" class="overlay">
    <div class="popup">
    <form action=<?php echo "enter-progress.php?type_id=".urlencode($_GET["type_id"]); ?> method="POST">
          <h2>Enter Progress</h2>

            <div class="input-group">
                <label for="progress">Enter Progress</label>
                <input type="number" id="progress" name="progress" required>
            </div>

            <div>
                <button class="cancel-btn" onclick="closePopup3()">Cancel</button>
                <button class="submit-btn" id="submit" type="submit" name="submit">Submit</button>
            </div>
    </form>
    </div>
    </div>

<h2>Your Progress Visualization</h2>

<div id="charts-container">
<?php
// Query to aggregate progress by date for each user and habit type
$sql = "SELECT user_id, habit_type_id, DATE(timestamp) as date, SUM(progress) as total_progress
    FROM user_habit_progress
    WHERE user_id = ? 
    GROUP BY user_id, habit_type_id, DATE(timestamp)
    ORDER BY DATE(timestamp)
";

// Assuming you have a session with user ID stored in `$_SESSION['user_id']`
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']); // Use the logged-in user's ID
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $habit_type_id = $row['habit_type_id'];
    $date = $row['date'];
    $total_progress = $row['total_progress'];

    // Format the data into a structure where habit_type_id is the key
    if (!isset($data[$habit_type_id])) {
        $data[$habit_type_id] = [];
    }

    $data[$habit_type_id][] = [
        'date' => $date,
        'progress' => $total_progress
    ];
}
?>
</div>
  <script>
    <?php
      if (isset($_GET["type_id"]) && isset($_GET["action"])) {
        if ($_GET["action"] == "set-goal") {
          echo 
          "document.getElementById('overlay2').style.display = 'flex';
          document.getElementById('main-content').classList.add('greyed-out');";
        } else if ($_GET["action"] == "enter-progress") {
          echo 
          "document.getElementById('overlay3').style.display = 'flex';
        document.getElementById('main-content').classList.add('greyed-out');";
        }
      }
    ?>
    function openPopup() {
      document.getElementById('overlay').style.display = 'flex';
      document.getElementById('main-content').classList.add('greyed-out');
    }

    function closePopup() {
      document.getElementById('overlay').style.display = 'none';
      document.getElementById('main-content').classList.remove('greyed-out');
    }

    function openPopup2(habit_type_id) {
      window.location.href = 'member-dashboard.php?type_id=' + habit_type_id + "&action=set-goal";
      
    }

    function closePopup2() {
      document.getElementById('overlay2').style.display = 'none';
      document.getElementById('main-content').classList.remove('greyed-out');
      window.location.href = 'member-dashboard.php';
      
    }

    function openPopup3(habit_type_id) {
      window.location.href = 'member-dashboard.php?type_id=' + habit_type_id + "&action=enter-progress";
      
    }

    function closePopup3() {
      document.getElementById('overlay3').style.display = 'none';
      document.getElementById('main-content').classList.remove('greyed-out');
      window.location.href = 'member-dashboard.php';
    }
  </script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
var habitData = <?php echo json_encode($data); ?>;

window.onload = function () {
    // Loop through each habit_type_id and create a chart
    Object.keys(habitData).forEach((habitTypeId) => {
        const habitDataArray = habitData[habitTypeId];

        // Extract dates and progress values
        const labels = habitDataArray.map((entry) => entry.date);
        const progressValues = habitDataArray.map((entry) => entry.progress);

        // Create a new canvas for each habit type chart
        const canvasId = `chart-habit-${habitTypeId}`;
        const canvas = document.createElement("canvas");
        canvas.id = canvasId;

        // Append to the charts-container div
        document.getElementById('charts-container').appendChild(canvas);

        // Chart.js configuration for each habit
        const ctx = document.getElementById(canvasId).getContext("2d");
        new Chart(ctx, {
            type: "bar", // Change to "bar" if you prefer a bar chart
            data: {
                labels: labels, // X-axis (dates)
                datasets: [{
                    label: `Progress for Habit ${habitTypeId}`,  // Customize label
                    data: progressValues, // Y-axis (progress values)
                    backgroundColor: 'rgba(54, 162, 235, 0.6)', // Bar color
                    borderColor: 'rgba(54, 162, 235, 1)',  // Border color
                    tension: 0.1,  // Smoothness of the line
                    borderWidth: 2  // Line width
                }]
            },
            options: {
                responsive: true,  // Make the chart responsive
                plugins: {
                    legend: {
                        display: true,
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date',  // X-axis title
                        }
                    },
                    y: {
                        beginAtZero: true,  // Start Y-axis from zero
                        title: {
                            display: true,
                            text: 'Progress',  // Y-axis title
                        }
                    },
                },
            }
        });
    });
}
</script>
</script>

<?php
// Fetch leaderboard data
$habitTypeId = 1; // Replace with the habit type ID you want to rank users on (e.g., Exercise)
$sql = "
    SELECT u.id AS user_id, u.name AS user_name,
    SUM(uhp.progress) AS total_progress
    FROM users u
    INNER JOIN user_habit_progress uhp ON u.id = uhp.user_id
    WHERE uhp.habit_type_id = ?
    GROUP BY u.id
    ORDER BY total_progress DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $habitTypeId);
$stmt->execute();
$result = $stmt->get_result();

// Display leaderboard
echo "<h1>User Leaderboard for Habit ID: $habitTypeId</h1>";
echo "<table border='1'>
        <tr>
            <th>Rank</th>
            <th>User Name</th>
            <th>Total Progress</th>
        </tr>";

$rank = 1;
while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$rank}</td>
            <td>{$row['user_name']}</td>
            <td>{$row['total_progress']}</td>
          </tr>";
    $rank++;
}

echo "</table>";

// Close connection
$stmt->close();
$conn->close();
?>

</body>
</html>
</section>
</body>
</html>

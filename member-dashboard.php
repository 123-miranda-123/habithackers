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
    <div id = "title">
        <h1>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>
        <h1>Team: <?php echo htmlspecialchars($team_name); ?></p>
    </div>

<?php
$sql = "SELECT * FROM habit_types"; 
$result = $conn->query($sql);
?>
    <button class="open-btn" onclick="openPopup()">+ Create a New Habit</button>

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
                <?php while ($row = $result->fetch_assoc()) { ?>
            <option value="<?php echo $row['habit_name']; ?>" data-unit="<?php echo $row['unit']; ?>">
                <?php echo $row['habit_name'] . " (" . $row['unit'] . ")"; ?>
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
            <form action="set-goal.php" method="POST">
                <h2>Update Goal</h2>

                <div class="input-group">
                    <label for="number">Frequency (Goal)</label>
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
    <form action="enter-progress.php" method="POST">
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
                <button id = 'set-goal' class='open-btn' onclick='openPopup2()'>Update Goal</button>
                <button id = 'enter-progress' class='open-btn' onclick='openPopup3()'>Enter Progress</button>
                <button id = 'delete-goal'><a href='delete-goal.php?habit_type_id=" . $row['habit_type_id'] . "'>Delete</a></button>
              </td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p>No habits found for this user. Please add some habits.</p>";
}
?>

<div id="charts-container">
<?php
// Query to aggregate progress by date for each user and habit type
$sql = "
    SELECT user_id, habit_type_id, DATE(timestamp) as date, SUM(progress) as total_progress
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

// Output the data as JSON
echo "<script>var habitData = " . json_encode($data) . ";</script>";
?>
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

    function openPopup2() {
      document.getElementById('overlay2').style.display = 'flex';
      document.getElementById('main-content2').classList.add('greyed-out');
    }

    function closePopup2() {
      document.getElementById('overlay2').style.display = 'none';
      document.getElementById('main-content2').classList.remove('greyed-out');
    }

    function openPopup3() {
      document.getElementById('overlay3').style.display = 'flex';
      document.getElementById('main-content3').classList.add('greyed-out');
    }

    function closePopup3() {
      document.getElementById('overlay3').style.display = 'none';
      document.getElementById('main-content3').classList.remove('greyed-out');
    }
  </script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
window.onload = function () {
    // Fetch data from PHP (ensure the PHP script is correct)
    fetch('member-dashboard.php')
        .then((response) => response.json())
        .then((data) => {
            // Loop through each habit_type_id and create a chart
            Object.keys(data).forEach((habitTypeId) => {
                const habitData = data[habitTypeId];

                // Extract dates and progress values
                const labels = habitData.map((entry) => entry.date);
                const progressValues = habitData.map((entry) => entry.progress);

                // Create a canvas for each habit type chart
                const canvasId = `chart-habit-${habitTypeId}`;
                const canvas = document.createElement("canvas");
                canvas.id = canvasId;

                // Append to the charts-container div
                document.getElementById('charts-container').appendChild(canvas);

                // Chart.js configuration for each habit
                const ctx = document.getElementById(canvasId).getContext("2d");
                new Chart(ctx, {
                    type: "bar", // Change to "line" or other chart types if needed
                    data: {
                        labels: labels, // X-axis (dates)
                        datasets: [{
                            label: `Progress for Habit Type ${habitTypeId}`,
                            data: progressValues, // Y-axis (progress values)
                            backgroundColor: "rgba(54, 162, 235, 0.6)",
                            borderColor: "rgba(54, 162, 235, 1)",
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                            },
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: "Date",
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: "Progress",
                                }
                            },
                        },
                    }
                });
            });
        })
        .catch((error) => {
            console.error("Error loading chart data:", error);
        });
}

</script>


</body>
</html>
</section>
</body>
</html>

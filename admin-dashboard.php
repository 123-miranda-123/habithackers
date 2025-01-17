<?php
session_start();
require_once "database.php";

if ((strtolower($_SESSION["user_role"])) !== "admin") {
    header("Location: index.php");
}
// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Habit Hub</title>
    <link href="styles/header.css" rel="stylesheet" type="text/css"/>
    <link href="styles/member-dashboard.css" rel="stylesheet" type="text/css"/>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
    <link rel="icon" href="images/icon.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <script>
      function openEdit(id) {
        window.location.href = 'edit-user.php?id=' + encodeURIComponent(id);
      }
      function openCapEdit(id) {
        window.location.href = 'edit-teams.php?id=' + encodeURIComponent(id);
      }
      function openCapDelete(id) {
        window.location.href = 'delete-team.php?id=' + encodeURIComponent(id);
      }
      function openDelete(id) {
        window.location.href = 'delete-user.php?id=' + encodeURIComponent(id);
      }
    </script>
    

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
    

    <section class = "container">
        <h2>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h>
        <h1>Admin Dashboard</h1>
        <h2>Users</h2>
        <button class = "add"><a href = "new-user.php">+ New User</a></button>
        <table class = "users-table">
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            
            
            <?php
                
            $sql = "SELECT * FROM users";
            $result = $conn->query($sql);

            if (!$result) {
                echo "Invalid query: " . $conn->error;
                exit();
            }

            while ($row = $result->fetch_assoc()) {
                echo "
                <tr> 
                <td id = 'user-id'>" . $row["id"] . "</td>
                <td>" . $row["name"] . "</td>
                <td>" . $row["email"] . "</td>
                <td>" . $row["role"] . "</td>
                <td>
                  
                    <button id = 'edit' onclick = openEdit(".$row["id"].")>Edit</button>
                    <button id = 'delete' onclick = openDelete(".$row["id"].")>Delete</button>
                  
                </td>
            </tr>";
            }
            
            ?>
        </table>


        <h2>Teams</h2>
        <button class = "add"><a href = "new-team.php">+ New Team</a></button>
        <table class = "teams-table">
            <tr>
                <th>Team ID</th>
                <th>Team Name</th>
                <th>Team Captain </th>
                <th>Actions</th>
            </tr>
            <?php 
            $sql = "SELECT * from teams";
            $result = $conn->query($sql);
            if (!$result) {
                echo "Invalid query: ". $conn->error;
                exit();
            }
            while ($row = $result->fetch_assoc()) {
                $sql = "SELECT * FROM users WHERE id = " . $row["captain_id"];
                $res = $conn->query($sql);
                $inf = $res->fetch_assoc();
                echo "<tr> 
                <td id = 'team-id'>". $row["id"] . "</td>
                 <td>".$row["name"] . "</td>
                 <td>".$inf["name"]."</td>
                 <td>
                    <button id = 'edit' onclick = openCapEdit(".$row["id"].")>Edit</button>
                    <button id = 'delete' onclick = openCapDelete(".$row["id"].")>Delete</button>
                  
                </td>";
            }
            ?>
        </table>
    </section>

        <h2>Company Habit Logs</h2>
        <button class= "open-btn" onclick="openPopup()">+ Create a New Company Habit</button>

        <?php
        $sql = "SELECT company_habits.*, habit_types.habit_name, habit_types.unit 
        FROM company_habits
        JOIN habit_types ON company_habits.habit_type_id = habit_types.id";

        $stmt = $conn->prepare($sql);
        // Bind the team_id parameter from the session to the query
        $stmt->execute();
        $result = $stmt->get_result();


        if ($result->num_rows > 0) {
          echo "<table>";
          echo "<tr><th>Habit Type</th>
          <th>Company Progress</th>
          <th>Time Frame</th>
          <th>Actions</th></tr>";

            while ($row = $result->fetch_assoc()) {
                $habit_name = $row['habit_name'];
                $unit = $row['unit'];


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
                echo "<td>
                        <button id='set-goal' class='open-btn' onclick='openPopup2(".$row['habit_type_id'].")'>Update Goal</button>
                        <button id='delete-goal'><a href='delete-goal.php?habit_type_id=" . $row['habit_type_id'] . "'>Delete</a></button>
                      </td>";
                echo "<input type='hidden' name='habit_type_id' value='" . $row['habit_type_id'] . "'>";
                echo "</tr>";
            }

            echo "</table>";
}
?>

<?php
$sql_habit_type = "SELECT * FROM habit_types"; 
$result_habit_type = $conn->query($sql_habit_type);
?>
    <div id="overlay" class="overlay">
    <div class="popup">
    <form action="create-companyhabit.php" method="POST">
          <h2>Create a New Company Habit</h2>

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
            <form action=<?php echo "set-companygoal.php?type_id=".urlencode($_GET["type_id"]); ?> method="POST">
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
    
    
    <h2>Company Progress Visualization</h2>
    
    <div id="charts-container">
    <?php
    // Query to aggregate progress by date for each user and habit type
    $sql = "SELECT habit_type_id, habit_types.habit_name, habit_types.unit, DATE(timestamp) as date, SUM(progress) as total_progress
        FROM company_habit_progress
        INNER JOIN habit_types ON company_habit_progress.habit_type_id = habit_types.id
        GROUP BY habit_type_id, DATE(timestamp)
        ORDER BY DATE(timestamp)
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $habit_type_id = $row['habit_type_id'];
        $date = $row['date'];
        $total_progress = $row['total_progress'];
        $habit_name = $row['habit_name'];
        $unit = $row['unit'];

        // Format the data into a structure where habit_type_id is the key
        if (!isset($data[$habit_type_id])) {
            $data[$habit_type_id] = [];
        }

        $data[$habit_type_id][] = [
            'date' => $date,
            'progress' => $total_progress,
            'habit_name' => $habit_name,
            'unit' => $unit
        ];
    }
    ?>
    </div>

    <script>
        var habitData = <?php echo json_encode($data); ?>;

            const colors = {
                1: 'rgba(255, 99, 132, 0.6)',   // Red
                2: 'rgba(54, 162, 235, 0.6)',   // Blue
                3: 'rgba(75, 192, 192, 0.6)',   // Green
                4: 'rgba(153, 102, 255, 0.6)',  // Purple
                5: 'rgba(255, 159, 64, 0.6)',   // Orange
                6: 'rgba(255, 205, 86, 0.6)',   // Yellow
                7: 'rgba(156, 39, 176, 0.6)',   // Pink
                8: 'rgba(0, 123, 255, 0.6)'     // Blue
            };

        window.onload = function () {
            // Loop through each habit_type_id and create a chart
            Object.keys(habitData).forEach((habitTypeId) => {
                const habitDataArray = habitData[habitTypeId];

                // Extract dates, progress values, unit, and habit name
                const labels = habitDataArray.map((entry) => entry.date);
                const progressValues = habitDataArray.map((entry) => entry.progress);
                const unit = habitDataArray[0].unit;
                const habitName = habitDataArray[0].habit_name;

                // Create a new canvas for each habit type chart
                const canvasId = `chart-habit-${habitTypeId}`;
                const canvas = document.createElement("canvas");
                canvas.id = canvasId;

                const chartColor = colors[habitTypeId]

                // Append to the charts-container div
                document.getElementById('charts-container').appendChild(canvas);

                // Chart.js configuration for each habit
                const ctx = document.getElementById(canvasId).getContext("2d");
                const chart = new Chart(ctx, {
                    type: "bar", // Change to "bar" if you prefer a bar chart
                    data: {
                        labels: labels, // X-axis (dates)
                        datasets: [{
                            label: `Progress for ${habitName} (${unit})` ,  // Customize label with habit name
                            data: progressValues, // Y-axis (progress values)
                            backgroundColor: chartColor, // Bar color
                            borderColor: chartColor.replace('0.6', '1'),  // Border color
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
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: `${unit}` // Y-axis title
                                }
                            },
                        },
                    }
                });
            });
        }
    
    <?php
      if (isset($_GET["type_id"]) && isset($_GET["action"])) {
        if ($_GET["action"] == "set-goal") {
          echo 
          "document.getElementById('overlay2').style.display = 'flex';
          document.getElementById('main-content2').classList.add('greyed-out');";
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
      window.location.href = 'admin-dashboard.php?type_id=' + habit_type_id + "&action=set-goal";
      
    }

    function closePopup2() {
      document.getElementById('overlay2').style.display = 'none';
      document.getElementById('main-content').classList.remove('greyed-out');
      window.location.href = 'admin-dashboard.php';
      
    }
  </script>
</section>
</body>
</html>

